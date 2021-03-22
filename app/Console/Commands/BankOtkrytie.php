<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\City;
use App\Models\Tariff;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\DB;

class BankOtkrytie extends Command
{

    protected $signature = 'otkrytie:cron';


    protected $description = 'Обновление данных банка открытие';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
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
            $response = $client->request('GET',
                $bank_config['city'],
                ['headers' => $headers]
            )->getBody()->getContents();
            // тут добавляем города
            $response = json_decode($response);
            if ($response) {
                DB::table('cities')->truncate();
//                City::where('bank_id', $bank_id)->delete();
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
            $response = $client->request('GET',
                $bank_config['tariff'],
                ['headers' => $headers]
            )->getBody()->getContents();
            // тут добавляем тариф
            $response = json_decode($response);
            if ($response) {
//                Tariff::where('bank_id', $bank_id)->delete();
                DB::table('tariffs')->truncate();
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
}
