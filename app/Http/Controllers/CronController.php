<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{

    public function cron_city(){
        Artisan::call('otkrytie:cron');
    }

    public function cron_statusreport(){
        Artisan::call('statusreport:cron');

    }
    public function statusreport(){
        Artisan::call('duplicate:check');
        return redirect()->back();

    }

    public function cron_duplicate_check(){
        Artisan::call('duplicate:check');
    }


}
