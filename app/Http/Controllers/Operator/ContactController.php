<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Services\Bank1;
use App\Services\BankContact2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bank;
use App\Models\Contact;


class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $operator = Auth::user();
//        if ($operator->upload == 1) {

        $paginate = config('custom.paginate');
        $banks = Bank::orderBy('sort')->get();
        $count = 0;
        if ($request->has('q')) {
            $q = $request->q;
            $contacts = Contact::where('phone', 'LIKE', '%' . $q . '%')->orWhere('idbank', 'LIKE', '%' . $q . '%')->orWhere('inn', 'LIKE', '%' . $q . '%')->paginate($paginate);
        } elseif (count($request->all()) > 0) {
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
            $contacts = $queryContact->paginate($paginate);
            $count = $queryContact->count();
        } else {
            $contacts = Contact::paginate($paginate);
        }
        $data_banks = [];
        foreach ($contacts as $contact) {
            if ($contact->banks) {
                $data_banks[$contact->id] = [];
                foreach ($contact->banks as $bank) {
                    switch ($bank->id) {
                        case 1:
                            $data_banks[$contact->id][$bank->id] = Bank1::ContactData($bank, $contact);
                            break;
                        case 2:
                            $data_banks[$contact->id][$bank->id] = BankContact2::ContactData($bank, $contact);
                            break;
                        default:
                            $bank_data[$bank->id] = [
                                'value' => 0
                            ];;
                    }
                }
            }
        }
        $bank_config_all = config('bank');
        return view('operanorcontacs.index', [
            'contacts' => $contacts,
            'banks' => $banks,
            'data_banks' => $data_banks,
            'bank_config_all' => $bank_config_all,
            'count' => $count
        ]);
//        }
//        else {
//            return redirect('/no-access');
//        }

    }

    public function search(Request $request)
    {
        if ($request->has('q')) {
            $q = $request->q;
            $contact = Contact::where('phone', 'LIKE', '%' . $q . '%')->orWhere('idbank', 'LIKE', '%' . $q . '%')->orWhere('inn', 'LIKE', '%' . $q . '%')->first();
            if ($contact) {
                return redirect('/operatorcontacts/' . $contact->id . '/edit');
            } else {
                return view('operanorcontacs.notresult');
            }
        } else {
            return view('operanorcontacs.notresult');
        }
    }

    public function create()
    {

    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $banks = Bank::orderBy('sort')->get();
        $user_id = Auth::user();
        $bank_data = [];
        foreach ($banks as $bank) {
            switch ($bank->id) {
                case 1:
                    $bank_data[$bank->id] = Bank1::ContactData($bank, $contact);
                    break;
                case 2:
                    $bank_data[$bank->id] = BankContact2::ContactData($bank, $contact);
                    break;
                default:
                    $bank_data[$bank->id] = [
                        'value' => 0
                    ];;
            }
        }
        return view('operanorcontacs.edit', [
            'contact' => $contact,
            'banks' => $banks,
            'bank_data' => $bank_data,
            'user_id' => $user_id
        ]);
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {

    }
}
