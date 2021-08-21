<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Services\Bank1;
use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

use App\Models\City;
use App\Models\Tariff;
use App\Services\Bank2;

class StatusReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statusreport:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'проверка статуса отправленных заявок';

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
        ini_set('memory_limit', '2048M');
        $status = config('reports');

        /*
      $reports = Report::get();

      foreach ($reports as $report) {
          $bank_id = $report->bank_id;
          switch ($bank_id) {
              case 1:
                  if (in_array($report->status, $status['1'])) {
                      Bank1::check($report);
                  }
                  break;
              case 2:
                  if (in_array($report->status, $status['2'])) {
                      Bank2::check($report);
                  }
                  break;
          }
      }
      */

        /*
         *  новая выборка !!!!!!!!!!!!!
         */


    }
}
