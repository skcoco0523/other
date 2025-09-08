<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserLog extends Model
{
    use HasFactory;

    protected $table = 'user_logs';

    // マスアサインメント可能な属性
    protected $fillable = [
        'user_id',
        'ip_address',
        'type'
    ];

    public $timestamps = false; // タイムスタンプを無効にする

    //login, logout, prf_chg, ...
    public static function create_user_log($type)
    {
        $error_log = __FUNCTION__.".log";
        make_error_log($error_log,"-------start--------");
        $user_id = Auth::id();
        $ip_address = request()->ip();
        if($user_id && $type){
            make_error_log($error_log,"user_id=".$user_id."      type=".$type);
            return self::Create(
                [
                    'user_id' => $user_id,
                    'ip_address' => $ip_address,
                    'type' => $type,
                    'created_at' => now() // created_at を手動で設定
                ]
            );
        }else{
            make_error_log($error_log,"user_id or type is null");
            return null;
        }
    }

}
