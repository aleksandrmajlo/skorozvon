<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Bank;
use App\Models\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $paginate=50;
    public function index()
    {

        $user=Auth::user();
        $banks=Bank::orderBy('sort')->get();
        $reports=$user->reports()->paginate($this->paginate);
        $bank_config_all = config('bank');
        return view('operatorreports.index',[
            'reports' => $reports,
            'banks' => $banks,
            'bank_config_all' => $bank_config_all,
            'count'=>0,
            'filter_url'=>'/reports-filter-operator'
        ]);
    }

    public function filter(Request $request)
    {
        $banks = Bank::orderBy('sort')->get();

        $query = Report::orderBy('created_at', 'desc');
        $query->where('user_id', Auth::user()->id);


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
        if ($request->has('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }
        if (($request->has('date_start') && !empty($request->date_start)) && ($request->has('date_end') && !empty($request->date_end))) {
            $query->whereBetween('created_at', [$request->date_start. ' 00:00:00', $request->date_end . ' 23:59:59']);
        }
        if (($request->has('date_start') && !empty($request->date_start)) && empty($request->date_end)) {
            $query->where('created_at', '>=', $request->date_start . ' 00:00:00');
        }
        if (($request->has('date_end') && !empty($request->date_end)) && empty($request->date_start)) {
            $query->where('created_at', '<', $request->date_end . ' 23:59:59');
        }
        foreach ($banks as $bank) {
            if ($request->has('bank_' . $bank->id)&&$request->input('bank_' . $bank->id)!=='not') {
                $query->where('status', $request->input('bank_' . $bank->id));
                $query->where('bank_id', $bank->id);
            }
        }
        $reports = $query->paginate($this->paginate);
        $status = config('reports');
        $bank_config_all = config('bank');



        return view('operatorreports.index', [
            'reports' => $reports,
            'banks' => $banks,
            'status' => $status,
            'filter_url'=>'/reports-filter-operator'
        ]);

    }


    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
