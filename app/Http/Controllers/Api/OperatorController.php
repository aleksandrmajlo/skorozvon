<?php


namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
   public function logs(Request $request){
       $id=$request->id;
       $user=User::find($id);
       $res=[];
       $authentications=$user->authentications;
       if($authentications){
           foreach ($authentications as $authentication){
               $res[]=[
                   'ip'=>$authentication->ip_address,
                    'date'=>$authentication->login_at->format('Y-m-d H:i:s')
               ];
           }
       }
       return response()->json($res);
   }
}
