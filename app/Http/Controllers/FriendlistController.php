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
        if($input['friend_code']){
            $search_user[] = Friendlist::findByFriendCode($input['friend_code'],Auth::id());
            if(!$search_user[0]){
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
                $search_user = array();
            }
            $friendlist=null;
        }else{
            //0:承認待ち,1:承認済み,2:拒否
            $friendlist = Friendlist::getFriendlist(Auth::id());
        }

        $msg = null;

        //dd($friendlist,$search_user);
        //if($friendlist || $search_user){
            return view('friendlist_show', compact('friendlist', 'search_user', 'input', 'msg'));
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
        if (empty($input['table']))             $input['table']='mus';
        if (empty($input['bit_num']))           $input['bit_num']=null;
        //選択しているタブのﾍﾟｰｼﾞｬｰのみページを指定する
        //$art_page = ($input['table'] == "art") ? $input['page'] :1;
        $mus_page = ($input['table'] == "mus")      ? $input['page'] :1;
        //$alb_page = ($input['table'] == "alb")      ? $input['page'] :1;
        //$pl_page  = ($input['table'] == "pl")       ? $input['page'] :1;
        $cc_page  = ($input['table'] == "category") ? $input['page'] :1; //カテゴリ別>選択カテゴリ
        $favorite_list = array();
        $custom_category_list = array();

        //ユーザー検索
        //dd($input);
        $friend_profile = User::profile_get($input['friend_id']);
        //公開フラグ確認
        if(!isset($friend_profile) || $friend_profile->friend_status!="accepted"){
            // フレンドリストにリダイレクト\
            $message = ['message' => 'フレンド以外のデータは閲覧できません。', 'type' => 'error', 'sec' => '2000'];
            return redirect()->route('friendlist-show')->with($message);
        }

        //フレンド承認済みで相手の公開制限無し
        if($friend_profile->release_flag!=1 && $friend_profile->friend_status=="accepted"){

            if($input['table']=="mus"){
                $favorite_list["mus"] = Favorite::getFavorite(10,true,$mus_page,$input['friend_id'],"mus");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
            }
            //カテゴリ別タブ
            if($input['table']=="category"){
                $custom_category_list       = CustomCategory::getCustomCategory(null,false,null,null);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,ビット番号
                $favorite_list["category"]  = CustomCategory::getCustomCategory(10,true,$cc_page ,$input['friend_id'],$input['bit_num']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,ビット番号
            }
        }else{
            $favorite_list["mus"]       = null;
            $favorite_list["category"]  = null;
            //$favorite_list["alb"] = null;
            //$favorite_list["pl"]  = null;
        }

        //dd($custom_category_list,$favorite_list);
        $msg = null;
        if($favorite_list || $custom_category_list){
            return view('friend_show', compact('friend_profile', 'favorite_list', 'custom_category_list', 'input', 'msg'));
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
            $user_prf = User::profile_get($user_id);
            $send_info = [
                'title' => 'フレンド申請',
                'body' => $user_prf->name.'からフレンド申請が届きました',
                'url' => route('friendlist-show', ['table' => 'request']),
            ];
            push_send($friend_id,$send_info);
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
            $user_prf = User::profile_get($user_id);
            $send_info = [
                'title' => 'フレンド申請',
                'body' => $user_prf->name.'からフレンド申請が承諾されました',
                'url' => route('friendlist-show', ['table' => 'pending']),
            ];
            push_send($friend_id,$send_info);
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
