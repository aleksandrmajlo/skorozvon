<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class OperatorController extends Controller
{



    public function index()
    {
        $paginate=config('custom.paginate');
        $operators=User::where('role',2)->paginate($paginate);
        return view('operators.index',[
            'operators'=>$operators
        ]);
    }
    public function create()
    {
        return view('operators.create');
    }
    public function store(Request $request)
    {
        $rules=[
            'fio' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $validator=$request->validate($rules);
        User::create([
            'name' => $request->email,
            'email' => $request->email,
            'fio' => $request->fio,
            'role' => 2,
            'status' => $request->status,
            'upload' => $request->upload,
            'ContactDownload' => $request->ContactDownload,
            'password' => Hash::make($request->password),
        ]);
        return redirect('/operators')->with('status', 'Оператор создан!');

    }



    public function edit($id)
    {
        $operator=User::findOrFail($id);
        return  view('operators.edit',[
            'operator'=>$operator
        ]);
    }

    public function update(Request $request, $id)
    {
        if($request->password){
            $rules=[
                'fio' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8'],
            ];
        }else{
            $rules=[
                'fio' => ['required', 'string'],
            ];
        }
        $validator=$request->validate($rules);
        $operator=User::findOrFail($id);
        if ($request->password) {
            $operator->password=Hash::make($request->password);
        }
        $operator->fio=$request->fio;
        $operator->status=$request->status;
        $operator->upload=$request->upload;
        $operator->ContactDownload=$request->ContactDownload;
        $operator->save();

        return redirect('/operators/' . $operator->id . '/edit')->with('status', 'Оператор обновлен!');

    }


    public function destroy($id)
    {
        //
    }
}
