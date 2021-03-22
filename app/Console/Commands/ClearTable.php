<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class ClearTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear table';

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
        DB::table('contacts')->truncate();

//        DB::table('cities')->truncate();
//        DB::table('tariffs')->truncate();
        DB::table('contact_logs')->truncate();
        DB::table('logs')->truncate();
        DB::table('reports')->truncate();
        DB::table('dublicates')->truncate();
        DB::table('bank_contact')->truncate();

        // лог авторизаций
         DB::table('authentication_log')->truncate();

    }
}
