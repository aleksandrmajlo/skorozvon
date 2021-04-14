<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Services\Bank1;
use App\Services\Bank2;
use Illuminate\Console\Command;

use App\Models\City;
use App\Models\Tariff;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\DB;

class CityTariff extends Command
{

    protected $signature = 'otkrytie:cron';


    protected $description = 'Обновление город тарифов акций';

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
        DB::table('cities')->truncate();
        DB::table('tariffs')->truncate();
        DB::table('actions')->truncate();

        // города тарифф акция
        $banks=Bank::get();
        foreach ($banks as $bank){
            switch ($bank->id) {
                case 1:
                    Bank1::getCityTariff();
                    break;
                case 2:
                    Bank2::getCityTariff();
                    Bank2::ActionGet();
                    break;
            }
        }

    }
}
