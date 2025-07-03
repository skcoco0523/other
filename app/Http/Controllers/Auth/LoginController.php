<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\Models\UserLog; 
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    protected function authenticated(Request $request, $user)
    {
        //ログ書き込み
        //make_error_log("myplaylist_get.log","request=".$request);
        //make_error_log("myplaylist_get.log","user=".$user);
        UserLog::create_user_log("login");
        //dd($request, $user);

        // ユーザーが認証された後にデバイス情報を登録するためフロントで識別できるようにする
        return redirect()->intended($this->redirectPath() . '?login=success');
    }
    

}
