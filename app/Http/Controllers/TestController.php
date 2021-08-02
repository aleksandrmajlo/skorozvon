<?php

namespace App\Http\Controllers;


use App\Models\Bank;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Models\Dublicate;
use App\Models\Report;
use App\Services\Bank1;
use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Tariff;
use App\Services\Bank2;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TestController extends Controller
{

    // проверка на дубли отправка заявки
    public function sendBankContacDuplicate(Request $request)
    {
        $contact_id = 7;
//        $contact_id = 95018;
        $contact = Contact::find($contact_id);
        $inns = [$contact->inn];
        $banks = Bank::get();
        foreach ($banks as $bank) {
            switch ($bank->id) {
                case 1:
                    Bank1::InnDublicate($inns,$contact_id,$contact->phone);
                    break;
                case 2:
//                    Bank2::InnDublicate($inns,$contact_id);
                    break;
            }
        }
        /*
        // лог контакта
        $contactlog = new ContactLog;
        $contactlog->type = '3';
        $contactlog->user_id = Auth::user()->id;
        $contactlog->contact_id = $contact_id;
        $contactlog->save();
        response()->json(['suc' => true]);
        */
    }

    public function Zajvka(){

        /*
         * acquiring: 0
action_id: null
bank_id: 1
city_id: "bba16a72-09e1-4f3f-93af-df5ecab6714b"
comment: ""
contact_id: 3
tariff_id: "LP_RKO"
         */
        $bank_id = 1;
        $city_id = "bba16a72-09e1-4f3f-93af-df5ecab6714b";
        $city = City::where('idd', $city_id)->first();
        $tariff_id = "LP_RKO";
        $action_id = null;
        $contact_id = 95018;
        $comment = '';
        $acquiring = 0;



        $result = Bank1::send($contact_id, $tariff_id, $city,$comment,$action_id,$acquiring);

        $report = new Report;
        $report->bank_id = $bank_id;
        $report->city = $city->title;
        $report->tariff_id = $tariff_id;
        $report->contact_id = $contact_id;
        $report->user_id = Auth::user()->id;
        $report->input = $result['input'];
        $report->idd = $result['idd'];
        if ($result['input']) {
            $report->status = 0;
        } else {
            $report->status = $result['status'];
        }
        $report->save();

        if($result['status']=='inqueue'){
            //обновляем статус
            $bank_config_all = config('bank');
            $message=$bank_config_all[$bank_id]['statusText']['inqueue']['text'];
            DB::table('bank_contact')
                ->where('bank_id', $bank_id)
                ->where('contact_id', $contact_id)
                ->update(['status' => $result['status'],'message'=>$message]);
        }
    }

    public function  check(){

        $status = config('reports');
        $reports = Report::get();
//        $counter = 0;
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
//                        Bank2::check($report);
                    }
                    break;
            }
        }

    }

}
