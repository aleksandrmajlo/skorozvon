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
        /*
        // тариф
        try {
            $response = $client->request('GET',
                $bank_config['tariff'],
                ['headers' => $headers]
            )->getBody()->getContents();
            // тут добавляем тариф
            $response = json_decode($response);
            if ($response) {

                foreach ($response->tariffs as $item) {
                    $tariff = new Tariff();
                    $tariff->title = $item->name;
                    $tariff->idd = $item->id;
                    $tariff->bank_id = self::$bank_id;
                    $tariff->save();
                }
            }
        } catch (RequestException $e) {
            echo Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\Message::toString($e->getResponse());
            }
        }
        */

    }
}
