<?php

namespace App\Http\Controllers\Export;


use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Contact;
use App\Services\BankContact2;
use Illuminate\Http\Request;

use App\Exports\ContactsExport;
use Maatwebsite\Excel\Facades\Excel;


class ExportController extends Controller
{
    public function contacts(Request $request)
    {
        $banks = Bank::orderBy('sort')->get();
        $queryContact = Contact::query();
        if ($request->has('type') && $request->type !== 'all') {
            if ($request->type == 'ip') {
                $queryContact->orWhereRaw('length(inn)=12');
            }
            if ($request->type == 'ooo') {
                $queryContact->orWhereRaw('length(inn)=10');
            }
        }
        foreach ($banks as $bank) {
            if ($request->has('bank_' . $bank->id) && $request->input('bank_' . $bank->id) !== 'not') {
                $data = [
                    'bank_id' => $bank->id,
                    'status' => $request->input('bank_' . $bank->id)
                ];
                $queryContact->whereHas('banks', function ($q) use ($data) {
                    return $q->where('bank_id', '=', $data['bank_id'])->where('status', '=', $data['status']);
                });
            }
        }
        // не существует
        if ($request->has('bank_2') && $request->bank_2 == 'not') {
            $queryContact->doesnthave('banks');
        }
        if ($request->has('start') && !empty($request->start) && $request->has('end') && !empty($request->end)) {
            $queryContact->where('created_at', '>=', $request->start . ' 00:00:00');
            $queryContact->where('created_at', '<=', $request->end . ' 23:59:59');
        } elseif ($request->has('start') && !empty($request->start)) {
            $queryContact->where('created_at', '>=', $request->start . ' 00:00:00');
        } elseif ($request->has('end') && !empty($request->end)) {
            $queryContact->where('created_at', '<=', $request->end . ' 23:59:59');
        }

        $contacts = $queryContact->get();

        if ($contacts) {
            $results = [];
            $results[] = [
                'id',
                'organizationName',
                'fullName',
                'email',
                'inn',
                'phone',
                'address',
                'Открытие'
            ];
            $bank_config_all = config('bank');
            foreach ($contacts as $contact) {
                $bank2='';
                if ($contact->banks) {
                    foreach ($contact->banks as $bank) {
                        switch ($bank->id) {
                            case 2:

                                $data= BankContact2::ContactData($bank, $contact);
                                $bank2=$data['date'].' '.$data['statusText']["text"];
                                break;

                        }
                    }
                }
                $results[] = [
                    $contact->idbank.' ',
                    $contact->organization,
                    $contact->fullname,
                    $contact->email,
                    $contact->inn.' ',
                    $contact->phone.' ',
                    $contact->address,
                    $bank2
                ];
            }
            $export = new ContactsExport($results);
            $newDate = date("Y_m_d_h_i");
            return Excel::download($export, $newDate . '.xlsx');

        }

    }
}
