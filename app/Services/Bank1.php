<?php


namespace App\Services;

use App\Models\Action;
use App\Models\City;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Models\Dublicate;


use App\Models\Log;
use App\Models\Tariff;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Bank1
{
    private static $bank_id = 1;
    // получение городов тарифов
    public static function getCityTariff()
    {
        $bank_config = config('bank.1');
        $headers = [
            'api-key' => $bank_config['token'],
            'content-type' => 'multipart/form-data;application/json;charset=UTF-8',
        ];
        $client = new Client([
            'base_uri' => $bank_config['host'],
        ]);

        // город
        try {
            $response = $client->request(
                'GET',
                $bank_config['city'],
                ['headers' => $headers]
            )->getBody()->getContents();
            // тут добавляем города
            $response = json_decode($response);
            if ($response) {

                foreach ($response->values as $item) {
                    $city = new City();
                    $city->title = $item->name;
                    $city->idd = $item->id;
                    $city->bank_id = self::$bank_id;
                    $city->save();
                }
            }
        } catch (RequestException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\Message::toString($e->getResponse());
            }
        }

        // тариф
        foreach ($bank_config['tariff'] as $key => $item) {
            $tariff = new Tariff();
            $tariff->title = $item;
            $tariff->idd = $key;
            $tariff->bank_id = self::$bank_id;
            $tariff->save();
        }
    }
    // отправка запроса на дублирование
    public static function InnDublicate($inn, $contact_id, $phone)
    {
        //дописать проверку на дубли !!!!!!!!!!!!!!
        // если есть null не проверяем
        $count = DB::table('bank_contact')->where('contact_id', $contact_id)
            ->where('bank_id', self::$bank_id)->count();
        if ($count > 0) return false;

        $bank_config = config('bank.' . self::$bank_id);
        $headers = [
            'api-key' => $bank_config['token'],
            'content-type' => 'application/json;charset=UTF-8',
        ];
        $client = new Client([
            'base_uri' => $bank_config['host'],
        ]);

        if (env('APP_ENV') === 'testing') {
            $url = $bank_config['inn_dublicate_test'];
        } else {
            $url = $bank_config['inn_dublicate'];
        }
        try {
            $phone = str_replace('+', '', $phone);
            $response = $client->post($url, [
                'headers' => $headers,
                RequestOptions::JSON => [
                    'organizationInfo' => ['inn' => $inn[0]],
                    "contactInfo" => [["phoneNumber" => $phone]],
                    "productInfo" => [["productCode" => $bank_config['default_tariff']]]
                ],
            ])->getBody()->getContents();
            $response = json_decode($response);

            if (is_null($response)) {
                $log = Log::create([
                    'request' => ['inns' => $inn],
                    'answer' => $response,
                    'type' => 'POST ' . $bank_config['host'] . $url,
                ]);
                DB::table('bank_contact')->insert([
                    'contact_id' => $contact_id,
                    'bank_id' => self::$bank_id,
                    'status' => 'success',
                    'message' => 'ПРОВЕРКА ВЫПОЛНЕНА. ДУБЛЕЙ НЕТ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        } catch (RequestException $e) {

            $error = Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                $error .= Psr7\Message::toString($e->getResponse());
            }
            $log = Log::create([
                'request' => ['inns' => $inn],
                'answer' => ['error' => $error],
                'type' => 'POST ' . $bank_config['host'] . $url,
            ]);

            DB::table('bank_contact')->insert([
                'contact_id' => $contact_id,
                'bank_id' => self::$bank_id,
                'status' => 'fail',
                'message' => $e->getMessage(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
    // отправка заяки  в банк!!!!!!
    public static function send($contact_id, $tariff_id, $city, $comment = '', $action_id = '', $acquiring = 0)
    {

        $bank_config = config('bank.' . self::$bank_id);
        $contact = Contact::find($contact_id);
        $result = [
            'idd' => null,
            'input' => null
        ];
        if (env('APP_ENV') === 'testing') {
            $headers = [
                'api-key' => $bank_config['token-demo'],
                'content-type' => 'application/json;charset=UTF-8',
            ];
        } else {
            $headers = [
                'api-key' => $bank_config['token'],
                'content-type' => 'application/json;charset=UTF-8',
            ];
        }
        $client = new Client([
            'base_uri' => $bank_config['host'],
        ]);
        $url = $bank_config['add'];
        try {
            $phone = $contact->phone;
            $phone = str_replace('+', '', $phone);
            $response = $client->post($url, [
                'headers' => $headers,
                RequestOptions::JSON => [
                    'organizationInfo' => [
                        'organizationName' => $contact->organization,
                        'inn' => $contact->inn
                    ],
                    "contactInfo" => [
                        [
                            "fullName" => $contact->fullname,
                            "phoneNumber" => $phone,
                            "contactEmail" => $contact->email,
                        ]
                    ],
                    "requestInfo" => [
                        [
                            "comment" => $comment,
                            "cityCode" => $city->idd
                        ]
                    ],
                    "productInfo" => [
                        [
                            "productCode" => $tariff_id
                        ]
                    ]
                ],
            ])->getBody()->getContents();
            $response = json_decode($response);
            $result['idd'] = $response->id;
            $result['status'] = 'inqueue';

            // логирование
            $log = Log::create([
                'request' => [
                    'full_name' => $contact->fullname,
                    'inn' => $contact->inn,
                    'phone' => $contact->phone,
                    'tariff_id' => $tariff_id,
                    'city' => $city->title,
                    'bank_id' => self::$bank_id,
                    'comment' => $comment,
                    'action_id' => $action_id,
                    'acquiring' => $acquiring
                ],
                'answer' => ['idd' => $response->id],
                'type' => 'POST ' . $bank_config['host'] . $url,
            ]);
            // логирование для контактов
            $contactlog = new ContactLog;
            $contactlog->type = '5';
            $contactlog->user_id = Auth::user()->id;
            $contactlog->contact_id = $contact->id;
            $contactlog->bank_id = self::$bank_id;
            $contactlog->save();
        } catch (RequestException $e) {

            $result['input'] = Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                $result['input'] = $result['input'] . Psr7\Message::toString($e->getResponse());
            }
            // логирование
            $log = Log::create([
                'request' => [
                    'contact_id' => $contact_id,
                    'tariff_id' => $tariff_id,
                    'bank_id' => self::$bank_id,
                    'city' => $city->title,
                ],
                'answer' => ['error' => $result['input']],
                'type' => 'POST ' . $bank_config['host'] . $url,
            ]);
        }
        return $result;
    }
    // получение статуса заявки в банке
    public static function check($report)
    {
        $id = $report->idd;
        $bank_config = config('bank.' . $report->bank_id);

        $headers = [
            'api-key' => $bank_config['token'],
            'content-type' => 'multipart/form-data;application/json;charset=UTF-8',
        ];
        $client = new Client([
            'base_uri' => $bank_config['host'],
        ]);
        $url = $bank_config['get_status'] . $id;

        try {
            $response = $client->request('GET', $url, [
                'headers' => $headers
            ])->getBody()->getContents();
            $response = json_decode($response);
            // логирование
            $log = Log::create([
                'request' => [
                    'report' => $report,
                    'bank_id' => self::$bank_id
                ],
                'answer' => $response,
                'type' => 'GET ' . $bank_config['host'] . $url,
            ]);
            // логирование end
            $report->status = $response->state;
            $report->save();

            DB::table('bank_contact')
                ->where('contact_id', $report->contact_id)
                ->where('bank_id', self::$bank_id)
                ->update([
                    'status' => $response->state,
                    'message' => $response->status,
                    'user_id' => $report->user_id,
                    'updated_at' => Carbon::now()
                ]);

            $user_id = null;
            if (Auth::user()) {
                $user_id = Auth::user()->id;
            }
            // логирование для контактов
            $contactlog = new ContactLog;
            $contactlog->type = '6';
            $contactlog->user_id = $user_id;
            $contactlog->contact_id = $report->contact_id;
            $contactlog->bank_id = self::$bank_id;
            $contactlog->status = $response->state;
            $contactlog->save();
        } catch (RequestException $e) {

            $error = Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                $error .= Psr7\Message::toString($e->getResponse());
            }
            // логирование
            $log = Log::create([
                'request' => [
                    'report' => $report,
                    'bank_id' => self::$bank_id
                ],
                'answer' => ['error' => $error],
                'type' => 'GET ' . $bank_config['host'] . $url,
            ]);
        }
    }
    // получение данных для этого контакта
    public static function ContactData($bank, $contact)
    {

        // тут проверка или есть уже отношение банк
        $bank_data = [];
        $bank_config_all = config('bank');
        $bank_config = $bank_config_all[self::$bank_id];

        $r = $bank->contacts()->where('id', $contact->id)->first();
        if ($r) {
            if (isset($bank_config['statusText'][$r->pivot->status])) {
                // при проверки заявки
                $bank_data = [
                    'date' => $r->pivot->updated_at,
                    'value' => $bank_config['statusText'][$r->pivot->status]['status'],
                    'status' => $r->pivot->status, //'status' => 0, = показывать кнопку отправки заявки
                    // это переделываем!!!!!!!!!
                    'statusText' => $bank_config['statusText'][$r->pivot->status],
                    'message' => $r->pivot->message // 'это выводится в описании
                ];
            } else {
                // проверка или отправлялась заявка
                $report = $bank->reports()->where('contact_id', $contact->id)->first();
                if ($report) {
                    // если да и статус 1 - то есть только отправилась
                    if ($report->status == 1) {
                        $bank_data = [
                            'date' => $report->updated_at,
                            'value' => 2
                        ];
                    }
                }
            }
        }

        if (empty($bank_data)) {
            $bank_data = [
                'value' => -1
            ];
        }
        return $bank_data;
    }
}
