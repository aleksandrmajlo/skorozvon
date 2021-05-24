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
                    Bank2::InnDublicate($inns,$contact_id);
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

}
