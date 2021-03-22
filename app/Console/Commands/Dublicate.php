<?php

namespace App\Console\Commands;

use App\Services\Bank2;
use Illuminate\Console\Command;

class Dublicate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplicate:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка проверки на дубли';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $duplikates= \App\Models\Dublicate::active()->get();
        foreach ($duplikates as $duplikate){
            switch ($duplikate->bank_id) {
                case 2:
                    Bank2::InnDublicateCheck($duplikate);
                    break;
            }
        }
    }
}
