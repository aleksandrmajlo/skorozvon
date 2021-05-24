<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\City;
use App\Models\Contact;
use App\Models\Report;
use App\Models\ContactLog;


use App\Services\Bank2;
use App\Services\Bank1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;


class ContactAjax extends Controller
{
    // обновление контактов
    public function update(Request $request)
    {
        $contact = Contact::find($request->id);
        $log = new ContactLog;
        $log->user_id = Auth::user()->id;
        $log->contact_id = $request->id;
        $log->type = '2';
        $log->ip = $request->ip();
        $log->input = json_encode([
            'inn' => $contact->inn,
            'phone' => $contact->phone,
            'fullname' => $contact->fullname,
            'organization' => $contact->organization,
            'email' => $contact->email,
            'address' => $contact->address,
        ]);
        $log->input_new = json_encode([
            'inn' => $request->inn,
            'phone' => $request->phone,
            'fullname' => $request->fullname,
            'organization' => $request->organization,
            'email' => $request->email,
            'address' => $request->address,
        ]);
        $log->save();

        $contact->inn = $request->inn;
        $contact->phone = $request->phone;
        $contact->fullname = $request->fullname;
        $contact->organization = $request->organization;
        $contact->email = $request->email;
        $contact->address = $request->address;
        $contact->save();

        return response()->json(['suc' => true]);
    }

    public function log(Request $request)
    {
        $id = $request->id;
        $contactlogs = ContactLog::where('contact_id', $id)->orderBy('id', 'desc')->get();
        $config = config('contactlog');
        $res_contactlogs = [];
        foreach ($contactlogs as $contactlog) {
            $bank = '';
            $status = $contactlog->status;
            if ($contactlog->bank_id) {
                $bank = $contactlog->bank->name;
                $config_bank = config('bank');
                if (isset($config_bank[$contactlog->bank_id]['statusText'][$contactlog->status])) {
                    $status = $config_bank[$contactlog->bank_id]['statusText'][$contactlog->status]['text'];
                }
            }
            $user='cron';
            if($contactlog->user_id){
                $user=$contactlog->user->email;
            }
            $res_contactlogs[] = [
                'input' => json_decode($contactlog->input),
                'input_new' => json_decode($contactlog->input_new),
                'type' => $config[$contactlog->type],
                'user' => $user,
                'date' => $contactlog->created_at->format('d-m-Y h:i:s'),
                'status' => $status,
                'bank' => $bank,

            ];
        }
        return response()->json([
            'contactlogs' => $res_contactlogs,
        ]);

    }

    // отправка данных в банк
    public function sendBankContac(Request $request)
    {

        $bank_id = $request->bank_id;
        $city_id = $request->city_id;
        $city = City::where('idd', $city_id)->first();
        $tariff_id = $request->tariff_id;
        $action_id = $request->action_id;
        $contact_id = $request->contact_id;
        $comment = $request->comment;
        $acquiring = $request->acquiring;

        switch ($bank_id) {
            case 2:
                $resust = Bank2::send($contact_id, $tariff_id, $city,$comment,$action_id,$acquiring);
                break;
        }

        $report = new Report;
        $report->bank_id = $request->bank_id;
        $report->city = $city->title;
        $report->tariff_id = $request->tariff_id;
        $report->contact_id = $request->contact_id;
        $report->user_id = Auth::user()->id;
        $report->input = $resust['input'];
        $report->idd = $resust['idd'];
        if ($resust['input']) {
            $report->status = 0;
        } else {
            $report->status = $resust['status'];
        }
        $report->save();


        return response()->json([
            'suc' => true
        ]);
    }

    // проверка на дубли отправка заявки
    public function sendBankContacDuplicate(Request $request)
    {
        $contact_id = $request->contact_id;
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
        // лог контакта
        $contactlog = new ContactLog;
        $contactlog->type = '3';
        $contactlog->user_id = Auth::user()->id;
        $contactlog->contact_id = $contact_id;
        $contactlog->save();
        response()->json(['suc' => true]);
    }

}
