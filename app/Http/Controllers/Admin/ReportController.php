<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $paginate = 50;

    public function index()
    {
        $reports = Report::orderBy('created_at', 'desc')->paginate($this->paginate);
        $banks = Bank::orderBy('sort')->get();
        $operators = User::activeoperator()->orderBy('created_at')->get();
        $bank_config_all = config('bank');

        return view('reports.index', [
            'reports' => $reports,
            'banks' => $banks,
            'operators' => $operators,
            'bank_config_all' => $bank_config_all,
            'count'=>0,
            'filter_url'=>'/reports-filter'
        ]);
    }

    public function filter(Request $request)
    {
        $banks = Bank::orderBy('sort')->get();
        $query = Report::orderBy('updated_at', 'desc');

        $count=0;
        if ($request->has('type') && $request->type !== 'all') {
            $contact_type = $request->type;
            $query->whereHas('contact', function ($q) use ($contact_type) {
                if ($contact_type == 'ip') {
                    return $q->WhereRaw('length(inn)=12');
                }
                if ($contact_type == 'ooo') {
                    return $q->WhereRaw('length(inn)=10');
                }
            });
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        /*
        if (($request->has('date_start') && !empty($request->date_start)) && ($request->has('date_end') && !empty($request->date_end))) {
            $query->whereBetween('created_at', [$request->date_start. ' 00:00:00', $request->date_end. ' 23:59:59']);
        }
        if (($request->has('date_start') && !empty($request->date_start)) && empty($request->date_end)) {
            $query->where('created_at', '>=', $request->date_start . ' 00:00:00');
        }
        if (($request->has('date_end') && !empty($request->date_end)) && empty($request->date_start)) {
            $query->where('created_at', '<', $request->date_end . ' 23:59:59');
        }
        */


        if (($request->has('date_start') && !empty($request->date_start)) && ($request->has('date_end') && !empty($request->date_end))) {
            $query->whereBetween('updated_at', [$request->date_start. ' 00:00:00', $request->date_end. ' 23:59:59']);
        }
        if (($request->has('date_start') && !empty($request->date_start)) && empty($request->date_end)) {
            $query->where('updated_at', '>=', $request->date_start . ' 00:00:00');
        }
        if (($request->has('date_end') && !empty($request->date_end)) && empty($request->date_start)) {
            $query->where('updated_at', '<', $request->date_end . ' 23:59:59');
        }


        foreach ($banks as $bank) {
            if ($request->has('bank_' . $bank->id)&&$request->input('bank_' . $bank->id)!=='not') {
                $query->where('status', $request->input('bank_' . $bank->id));
                $query->where('bank_id', $bank->id);
            }
        }

        $reports = $query->paginate($this->paginate);
        $count=$query->count();

        $operators = User::activeoperator()->orderBy('created_at')->get();
        $status = config('reports');
        $bank_config_all = config('bank');

        return view('reports.index', [
            'reports' => $reports,
            'banks' => $banks,
            'operators' => $operators,
            'status' => $status,
            'bank_config_all' => $bank_config_all,
            'count'=>$count,
            'filter_url'=>'/reports-filter'
        ]);

    }

}
