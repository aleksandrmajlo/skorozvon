<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ShippingController extends Controller
{

    // настройки
    public function setting()
    {
        $settings = DB::table('settings')->get();
        return response()->json([
            'setting1' => $settings[0]->value,
            'setting2' => $settings[1]->value,
        ]);
    }

    public function setting_send(Request $request)
    {
        DB::table('settings')
            ->where('id', 1)
            ->update(['value' => $request->setting1]);

        DB::table('settings')
            ->where('id', 2)
            ->update(['value' => $request->setting2]);
        return response()->json(['suc' => true]);
    }

    // привелегии пользователей
    public function permission()
    {
        $operators = User::activeoperator()->orderBy('created_at')->get();
        $banks = Bank::orderBy('sort')->get();
        $bank_users = DB::table('bank_user')->get();
        $res_bank_users = [];
        foreach ($bank_users as $bank_user) {
            if (isset($res_bank_users[$bank_user->user_id])) {
                $res_bank_users[$bank_user->user_id][] = $bank_user->bank_id;
            } else {
                $res_bank_users[$bank_user->user_id] = [];
                $res_bank_users[$bank_user->user_id][] = $bank_user->bank_id;
            }
        }
        return response()->json([
            'operators' => $operators,
            'banks' => $banks,
            'res_bank_users' => $res_bank_users
        ]);
    }

    // отправка данных
    public function permission_send(Request $request)
    {
        $checks = $request->check;
        DB::table('bank_user')->delete();
        if (!empty($checks)) {
            $data_users = [];
            foreach ($checks as $check) {
                $r = explode('_', $check);
                if (isset($data_users[$r[1]])) {
                    $data_users[$r[1]][] = $r[0];
                } else {
                    $data_users[$r[1]] = [];
                    $data_users[$r[1]][] = $r[0];
                }
            }
            foreach ($data_users as $key => $data_user) {
                foreach ($data_user as $item) {
                    DB::table('bank_user')->insert([
                        'user_id' => $key,
                        'bank_id' => $item
                    ]);
                }
            }
        }
        return response()->json(['suc' => true]);
    }


}
