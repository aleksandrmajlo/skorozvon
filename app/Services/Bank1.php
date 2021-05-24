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
            $response = $client->request('GET',
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
                // тут дубли не нужны
                /*
                $duplicate = Dublicate::create([
//                    'idd' => null,
                    'inns' => $inn,
                    'bank_id' => self::$bank_id
                ]);
                */
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
                'message' => 'Заявка есть. Проверка не пройдена(Отклонена. Дубль)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
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
                    'status' => $r->pivot->status,
                    'statusText' => $bank_config['statusText'][$r->pivot->status],
                    'message' => $r->pivot->message
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
