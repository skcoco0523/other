<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use Illuminate\Support\Str;

class UserController extends Controller
{
    public function password_reset_mailsend(Request $request)
    {
        //echo "start";
        $log_file = __FUNCTION__ . '.log';
        $mail = $request->email;
        make_error_log($log_file,"-------start-------");
        make_error_log($log_file,"chk_address=".$mail);
        // 一致するユーザーを検索
        $user = DB::table('users')->where('email', $mail)->first();
        //echo $send_addr;
        
        if($user){

            // パスワードリセットトークンを生成してDBに保存する
            $token = Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $mail],
                ['email' => $mail, 'token' => Hash::make($token), 'created_at' => now()]
                //['email' => $mail, 'token' => $token, 'created_at' => now()]
            );
            $send_info = new \stdClass();
            $send_info->name = $user->name;
            $send_info->mail = $mail;
            $send_info->token = $token;
            //$mail = $request->email; 取得済み
            
            $tmpl='password_reset';//  送信内容
            

            //パスワードリセットメール送信
            mail_send($send_info, $mail, $tmpl);
            // メール送信成功メッセージをセッションに設定
            Session::flash('status', __('passwords.sent'));
        } else {
            // ユーザーが見つからない場合のメッセージをセッションに設定
            Session::flash('status', __('passwords.user'));
        }

        // リダイレクト
        return redirect()->back();
    }
    //プロフィール情報取得
    public function profile_show(Request $request)
    {
        $this->middleware('auth');

        $profile = User::getProfile(Auth::id());;
        $msg = null;
        //dd($profile);
        if($profile){
            return view('auth/profile_show', compact('profile', 'msg'));
        }else{
            $message = ['message' => 'プロフィール情報の取得に失敗しました。','type' => '','sec' => '2000'];
            return redirect()->route('home')->with($message);
        }
    }
    //プロフィール情報変更
    public function profile_change(Request $request)
    {
        $this->middleware('auth');

        $after_profile = $request->all();
        $profile['id'] = Auth::id();
        //dd($profile);
        $ret = User::chgProfile($after_profile);
        $msg = null;
        if($ret['error_code'] == 0){        
            //$profile = Auth::user();
            //dd($profile);
            $message = ['message' => 'プロフィール情報を更新しました。',
                        'type' => 'profile',
                        'sec' => '2000'];
            return redirect()->route('profile-show')->with($message);
        }else{
            $message = ['message' => 'プロフィール情報の更新に失敗しました。',
                        'type' => 'error',
                        'sec' => '2000'];
            return redirect()->route('home')->with($message);
        }
    }

    

}
