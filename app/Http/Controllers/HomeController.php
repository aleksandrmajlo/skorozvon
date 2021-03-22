<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {

        $reportDateAll = Report::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->count();
//        $reportDateAllOpen = Report::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->open()->count();
        $reportDateAllOpen = DB::table('bank_contact')->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
                                                           ->where('status','created')->count();

        $reportWeekAll = Report::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
//        $reportWeekAllOpen = Report::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->open()->count();
        $reportWeekAllOpen = DB::table('bank_contact')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                                            ->where('status','created')->count();

        $reportMonthAll = Report::whereMonth('created_at', '=', date('m'))->count();
//        $reportMonthAllOpen = Report::whereMonth('created_at', '=', date('m'))->open()->count();
        $reportMonthAllOpen = DB::table('bank_contact')->whereMonth('created_at', '=', date('m'))
                                                            ->where('status','created')->count();

        $reportAll = Report::count();
//        $reportAllOpen = Report::open()->count();
        $reportAllOpen = DB::table('bank_contact')->where('status','created')->count();

        $contactDateAll = Contact::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->count();
        $contactWeekAll = Contact::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $contactMonthAll =Contact::whereMonth('created_at', '=', date('m'))->count();
        $contactAll = Contact::count();

        return view('dashboardadmin', [

            'reportDateAll' => $reportDateAll,
            'reportDateAllOpen' => $reportDateAllOpen,

            'reportWeekAll' => $reportWeekAll,
            'reportWeekAllOpen' => $reportWeekAllOpen,

            'reportMonthAll' => $reportMonthAll,
            'reportMonthAllOpen' => $reportMonthAllOpen,

            'reportAll' => $reportAll,
            'reportAllOpen' => $reportAllOpen,

            'contactDateAll' => $contactDateAll,
            'contactWeekAll' => $contactWeekAll,
            'contactMonthAll' => $contactMonthAll,
            'contactAll' => $contactAll,

        ]);
    }

    public function operator()
    {
        $user_id = Auth::user()->id;

        $reportDateAll = Report::where('user_id', $user_id)->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->count();
//        $reportDateAllOpen = Report::where('user_id', $user_id)->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->open()->count();
        $reportDateAllOpen = DB::table('bank_contact')->where('user_id', $user_id)->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
            ->where('status','created')->count();

        $reportWeekAll = Report::where('user_id', $user_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
//        $reportWeekAllOpen = Report::where('user_id', $user_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->open()->count();
        $reportWeekAllOpen = DB::table('bank_contact')->where('user_id', $user_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status','created')->count();


        $reportMonthAll = Report::where('user_id', $user_id)->whereMonth('created_at', '=', date('m'))->where('user_id', $user_id)->count();
//        $reportMonthAllOpen = Report::where('user_id', $user_id)->whereMonth('created_at', '=', date('m'))->where('user_id', $user_id)->open()->count();
        $reportMonthAllOpen = DB::table('bank_contact')->where('user_id', $user_id)->whereMonth('created_at', '=', date('m'))
            ->where('status','created')->count();

        $reportAll = Report::where('user_id', $user_id)->count();
//        $reportAllOpen = Report::where('user_id', $user_id)->open()->count();
        $reportAllOpen = DB::table('bank_contact')->where('user_id', $user_id)->where('status','created')->count();


        return view('dashboardoperator', [

            'reportDateAll' => $reportDateAll,
            'reportDateAllOpen' => $reportDateAllOpen,

            'reportWeekAll' => $reportWeekAll,
            'reportWeekAllOpen' => $reportWeekAllOpen,

            'reportMonthAll' => $reportMonthAll,
            'reportMonthAllOpen' => $reportMonthAllOpen,

            'reportAll' => $reportAll,
            'reportAllOpen' => $reportAllOpen
        ]);

    }

}
