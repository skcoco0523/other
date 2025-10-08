<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Friendlist;
use App\Models\Favorite;
use App\Models\CustomCategory;
use App\Models\User;

class FriendlistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //フレンドリスト表示
    public function friendlist_show(Request $request)
    {
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        //if (empty($input['page']))              $input['page']=null;
        if (empty($input['friend_code']))       $input['friend_code']=null;
        if (empty($input['table']))             $input['table']='accepted';

        //dd($input);
        //ユーザー検索
        $search_user = array();
        
        $friendlist['search']= array();
        if($input['table']=='search'){
            if($input['friend_code']){
                $search_user = Friendlist::findByFriendCode($input['friend_code'],Auth::id());
                if(!$search_user){
                    //ユーザー検索で一致しなかった場合は場合はリダイレクトする
                    /*
                    $message = ['message' => 'ユーザーが見つかりませんでした。',
                                'type' => 'error',
                                'sec' => '2000'];
                    return redirect()->route('friendlist-show')->with($message);
                    */
                    // ビューを直接表示する場合もメッセージをセッションに保存
                    session()->flash('message', 'ユーザーが見つかりませんでした。');
                    session()->flash('type', 'error');
                    session()->flash('sec', '2000');
                }else{
                    $friendlist['search'][]= $search_user;
                }
            }
        }else{
            //0:承認待ち,1:承認済み,2:拒否
            $friendlist = Friendlist::getFriendlist(Auth::id());
        }
        //dd($friendlist);

        $msg = null;

        //dd($friendlist,$search_user);
        //if($friendlist || $search_user){
            return view('friendlist_show', compact('friendlist', 'input', 'msg'));
        //}else{
            //return redirect()->route('home')->with('error', 'エラーが発生しました');
        //}
    }    
    //フレンド情報表示
    public function friend_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['page']))              $input['page']=null;
        if (empty($input['table']))             $input['table']=null;
        //選択しているタブのﾍﾟｰｼﾞｬｰのみページを指定する
        $favorite_list = array();

        //ユーザー検索
        //dd($input);
        $friend_profile = User::getProfile($input['friend_id']);
        //公開フラグ確認
        if(!isset($friend_profile) || $friend_profile->friend_status!="accepted"){
            // フレンドリストにリダイレクト\
            $message = ['message' => 'フレンド以外のデータは閲覧できません。', 'type' => 'error', 'sec' => '2000'];
            return redirect()->route('friendlist-show')->with($message);
        }

        //フレンド承認済みで相手の公開制限無し
        if($friend_profile->release_flag!=1 && $friend_profile->friend_status=="accepted"){
        }else{
        }
        //dd($friend_profile);
        $msg = null;
        if($friend_profile){
            return view('friend_show', compact('friend_profile', 'input', 'msg'));
        }else{
            return redirect()->route('home')->with('error', 'エラーが発生しました');
        }
    }
    //フレンド申請
    public function friend_request(Request $request)
    {
        $user_id = Auth::id();
        $friend_id =  (int) $request->user_id;
        if(($user_id != $friend_id)){
            $status = Friendlist::requestFriend($user_id, $friend_id);
        }
        if($status){
            //ユーザーへ通知
            $msg = 'フレンド申請を送信しました。';
            //フレンドへ通知
            $user_prf = User::getProfile($user_id);
             
            $send_info = new \stdClass();
            $send_info->title = "フレンド申請";
            $send_info->body = $user_prf->name. "からフレンド申請が届きました";
            $send_info->url = route('friendlist-show', ['table' => 'request']);

            push_send($send_info, $friend_id);
        }else{
            $msg = 'フレンド申請の送信に失敗しました。';
        }
        
        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];
        
        return redirect()->route('friendlist-show')->with($message);
    }
    //フレンド申請承諾
    public function friend_accept(Request $request)
    {
        $user_id = Auth::id();
        $friend_id =  (int) $request->user_id;
        $status = Friendlist::acceptFriend($user_id, $friend_id);

        if($status){  
            //ユーザーへ通知
            $msg = 'フレンド申請を承諾しました。';
            //フレンドへ通知
            $user_prf = User::getProfile($user_id);
             
            $send_info = new \stdClass();
            $send_info->title = "フレンド申請";
            $send_info->body =  $user_prf->name. "からフレンド申請が承諾されました";
            $send_info->url = route('friendlist-show', ['table' => 'pending']);

            push_send($send_info, $friend_id);
        }else{
            $msg = 'フレンド申請の承諾に失敗しました。';
        }

        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];
        
        return redirect()->route('friendlist-show')->with($message);
    }
    //フレンド申請拒否
    public function friend_decline(Request $request)
    {
        $friend_id =  (int) $request->user_id;
        $status = Friendlist::declineFriend(Auth::id(), $friend_id);

        if($status)     $msg = 'フレンド申請を拒否しました。';
        else            $msg = 'フレンド申請の拒否に失敗しました。';

        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];

        return redirect()->route('friendlist-show')->with($message);
    }
    //フレンド申請キャンセル
    public function friend_cancel(Request $request)
    {
        $friend_id =  (int) $request->user_id;
        $status = Friendlist::cancelFriend(Auth::id(), $friend_id);

        if($status){
            $msg = 'フレンド申請を削除しました。';
        }else{
            $msg = 'フレンド申請の削除に失敗しました。';
        }

        $message = ['message' => $msg, 'type' => 'friend', 'sec' => '2000'];

        return redirect()->route('friendlist-show')->with($message);
    }
}
