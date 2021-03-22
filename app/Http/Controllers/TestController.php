<?php

namespace App\Http\Controllers;


use App\Models\Bank;
use App\Models\Contact;
use App\Models\Dublicate;
use App\Models\Report;
use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Tariff;
use App\Services\Bank2;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;


class TestController extends Controller
{

    public function index1()
    {

        $test = env('APP_ENV');
        $bank_id = 2;
        $json_city = '
        [
    {
        "id": "0731cca0-620a-44a2-b809-8374c1505d6e",
        "city": "Абакан"
    },
    {
        "id": "0ff29066-7d2b-45bd-a32d-9d197dfa0d0d",
        "city": "Агинское"
    },
    {
        "id": "ac9e1416-e517-4405-8d76-99c9515c984f",
        "city": "Ярославль"
    },
    {
        "id": "45ad0d40-00fa-41a9-aa6b-8232de1f4a5c",
        "city": "Ярцево"
    }
   ]';

        $json_tariff = '
        {
          "tariffs": [
        {
            "id": "1cf8afe8-24cd-4400-829f-b6a9ec4bfc11",
            "name": "Наименование тарифа 1"
        },
        {
            "id": "6bc016e9-4950-4c79-83ca-00fa1883fa0b",
            "name": "Наименование тарифа 2"
        }
        ]
        }
        ';

        $bank_config = config('bank.2');
        $headers = [
            'Authorization' => 'Bearer ' . $bank_config['token'],
            'Accept' => 'application/json',
            'content-type' => 'multipart/form-data',
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
        } catch (RequestException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\Message::toString($e->getResponse());
            }
            if ($test == 'testing') {
                $response = json_decode($json_tariff);
                if ($response->tariffs) {
                    Tariff::where('bank_id', $bank_id)->delete();
                    foreach ($response->tariffs as $item) {
                        $tariff = new Tariff();
                        $tariff->title = $item->name;
                        $tariff->idd = $item->id;
                        $tariff->bank_id = $bank_id;
                        $tariff->save();
                    }
                }
            }
        }
        // тариф
        try {
            $response = $client->request(
                'GET',
                $bank_config['tariff'],
                ['headers' => $headers]
            )->getBody()->getContents();
            // тут добавляем тариф
        } catch (RequestException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\Message::toString($e->getResponse());
            }
            if ($test == 'testing') {
                $response = json_decode($json_city);
                City::where('bank_id', $bank_id)->delete();
                foreach ($response as $item) {
                    $city = new City();
                    $city->title = $item->city;
                    $city->idd = $item->id;
                    $city->bank_id = $bank_id;
                    $city->save();
                }
            }
        }
    }


    public function index()
    {


        $bank_id = 2;
        $bank_config = config('bank.2');
        $headers = [
            'x-auth-token' => $bank_config['token'],
            'Accept' => 'application/json',
            'content-type' => 'multipart/form-data',
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
                City::where('bank_id', $bank_id)->delete();
                foreach ($response as $item) {
                    $city = new City();
                    $city->title = $item->city;
                    $city->idd = $item->id;
                    $city->bank_id = $bank_id;
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
        try {
            $response = $client->request(
                'GET',
                $bank_config['tariff'],
                ['headers' => $headers]
            )->getBody()->getContents();
            // тут добавляем тариф
            $response = json_decode($response);
            if ($response) {
                Tariff::where('bank_id', $bank_id)->delete();
                foreach ($response->tariffs as $item) {
                    $tariff = new Tariff();
                    $tariff->title = $item->name;
                    $tariff->idd = $item->id;
                    $tariff->bank_id = $bank_id;
                    $tariff->save();
                }
            }
        } catch (RequestException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\Message::toString($e->getResponse());
            }
        }
    }

    public function indexSend(Request $request)
    {

        $bank_id = $request->bank_id;
        $bank_id = 2;
        $city_id = $request->city_id;
        $city_id = "0731cca0-620a-44a2-b809-8374c1505d6e";
        $city = City::where('idd', $city_id)->first();

        $tariff_id = $request->tariff_id;
        $tariff_id = "679ac815-e5de-4ae6-b6f0-4b6e843166af";
        $contact_id = $request->contact_id;
        $contact_id = 787;


        switch ($bank_id) {
            case 2:
                $resust = Bank2::send($contact_id, $tariff_id, $city);
                break;
        }
        dump($resust);
        /*
        $contact = Contact::find($contact_id);
        $bank_config = config('bank.' . $bank_id);
        $headers = [
            'content-type: multipart/form-data',
            'x-auth-token: ' . $bank_config['token']
        ];
        $client = new Client([
            'base_uri' => $bank_config['host'],
        ]);
        if (env('APP_ENV') === 'testing') {
            $url = $bank_config['test_add'];
        } else {
            $url = $bank_config['add'];
        }
        $resust = [
            'idd' => null,
            'input' => null
        ];

        if (env('APP_ENV') === 'testing') {
            $url = $bank_config['test_add'];
        } else {
            $url = $bank_config['test_add'];
        }
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'multipart' => [
                    [
                        'name' => 'full_name',
                        'contents' => $contact->fullname
                    ],
                    [
                        'name' => 'inn',
                        'contents' => $contact->inn
                    ],
                    [
                        'name' => 'email',
                        'contents' => $contact->email
                    ],
                    [
                        'name' => 'phone',
                        'contents' => $contact->phone
                    ],
                    [
                        'name' => 'tariff',
                        'contents' => $tariff_id
                    ],
                    [
                        'name' => 'city',
                        'contents' => $city->title,
                    ],
                ]
            ])->getBody()->getContents();;
            $response = json_decode($response);
            $resust['idd'] = $response->id;


        } catch (RequestException $e) {
            $resust['input']=Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                $resust['input'] =$resust['input']. Psr7\Message::toString($e->getResponse());
            }
        }
        */
    }

    public function index_Check()
    {

        $reports = Report::send()->get();
        foreach ($reports as $report) {
            $bank_id = $report->bank_id;
            switch ($bank_id) {
                case 0:
                    break;
                case 1:
                    break;
                case 2:
                    Bank2::check($report);
                    break;
            }
        }

    }

    public function InnDublicate()
    {

        $banks = Bank::get();
        $contacts = Contact::get();
        $inns = [];
        foreach ($contacts as $key=> $contact) {
            if($key<4){
                $inns[] = $contact->inn;
            }
        }
        foreach ($banks as $bank) {
            switch ($bank->id) {
                case 2:
                    Bank2::InnDublicate($inns);
                    break;
            }
        }

    }
    public function InnDublicateCheck(){
        $duplikates=Dublicate::active()->get();
        foreach ($duplikates as $duplikate){
            switch ($duplikate->bank_id) {
                case 2:
                    Bank2::InnDublicateCheck($duplikate);
                    break;
            }
        }
    }

}
