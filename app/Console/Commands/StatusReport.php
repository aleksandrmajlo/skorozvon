<?php

namespace App\Console\Commands;

use App\Models\Report;
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
        $status = config('reports');
        $reports = Report::get();
        foreach ($reports as $report) {
            $bank_id = $report->bank_id;
            switch ($bank_id) {
                case 0:
                    break;
                case 1:
                    break;
                case 2:
                    if ($report->status == $status[$bank_id]) {
                        Bank2::check($report);
                    }
                    break;
            }
        }
    }
}
