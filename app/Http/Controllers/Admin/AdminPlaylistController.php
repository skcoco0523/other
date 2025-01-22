<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Playlist;
use App\Models\Album;
use App\Models\Music;
//use App\Models\FileTmp;

//プレイリストコントローラー
class AdminPlaylistController extends Controller
{
    //追加
    public function playlist_regist(Request $request)
    {
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->only(['name']);
        
        $input['admin_flag']            = true;
        $input['udate_desc']            = true;
        $playlist = Playlist::getPlaylist_list(5,false,null,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $msg = request('msg');
        return view('admin.admin_home', compact('playlist', 'input', 'msg'));
    }
    //追加
    public function playlist_reg(Request $request)
    {
        //$input = $request->only(['name', 'user_id', 'admin_flag']);
        $input = $request->all();
        $ret = Playlist::createPlaylist($input);
        if($ret['error_code']==0){
             $msg = "プレイリスト：{$input['name']} を追加しました。";
             $input=null;   //データ登録成功時 初期化
        }
        if($ret['error_code']==1) $msg = "プレイリスト名を入力してください。";
        if($ret['error_code']==-1) $msg = "プレイリスト：{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-playlist-reg', ['input' => $input, 'msg' => $msg]);
    }
    //削除
    public function playlist_del(Request $request)
    {
        //$input = $request->only(['pl_id','pl_name','keyword','admin_flag']);
        $input = $request->all();
        $ret = Playlist::delPlaylist($input);
        if($ret['error_code']==0)   $msg = "プレイリスト：{$input['pl_name']} を削除しました。";
        if($ret['error_code']==-1)  $msg = "プレイリスト：{$input['pl_name']} の削除に失敗しました。";
        return redirect()->route('admin-playlist-search', ['input' => $input, 'msg' => $msg]);
    }
    //検索
    public function playlist_search(Request $request)
    {
        //変更 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();

        $input['admin_flag']            = true;
        $input['search_playlist']       = get_proc_data($input,"search_playlist");
        $input['search_admin_flag']     = get_proc_data($input,"search_admin_flag");

        $input['page']                  = get_proc_data($input,"page");

        $input['chg_flg'] = 0;
        //dd($input);
        $input['pl_name_asc']            = true;
        $playlist = Playlist::getPlaylist_list(10,true,$input['page'],$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｷｰﾜｰﾄﾞ,ﾕｰｻﾞｰﾌﾗｸﾞ
        $msg = request('msg');
        $msg = ($msg==NULL && count($playlist) === 0) ? "検索結果が0件です。" : $msg;
        return view('admin.admin_home', compact('playlist', 'input', 'msg'));
    }
    //変更
    public function playlist_chg(Request $request)
    {
        //$input = $request->only(['id', 'name']);
        $input = $request->all();
        $ret = Playlist::chgPlaylist($input);


        if($ret['error_code']==1) $msg = "テーブルから変更対象を選択してください。";
        if($ret['error_code']==2) $msg = "プレイリスト名を入力してください。";
        if($ret['error_code']==-1) $msg = "プレイリスト：{$input['name']} の変更に失敗しました。";
        if($ret['error_code']==0) $msg = "プレイリスト：{$input['name']} に更新しました。";

        return redirect()->route('admin-playlist-search', ['input' => $input, 'msg' => $msg]);
    }
    //詳細変更
    public function playlist_chg_detail(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $input['admin_flag']            = true;
        $input['id']                    = get_proc_data($input,"id");
        $input['search_playlist']       = get_proc_data($input,"search_playlist");
        $input['search_admin_flag']     = get_proc_data($input,"search_admin_flag");

        //収録曲変更
        $playlist_detail = Playlist::getPlaylist_detail($input['id']);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
        $playlist = null;
            
        $msg = request('msg');

        return view('admin.admin_home', compact('playlist_detail', 'playlist', 'input', 'msg'));
    }
    //詳細変更用　楽曲検索
    public function playlist_detail_search(Request $request)
    {
        //追加 or 削除からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)  $input = request('input');
        else                                 $input = $request->all();
        $input['admin_flag']    = true;
        $input['id']            = get_proc_data($input,"id");
        $input['page']          = get_proc_data($input,"page");
        //全件検索
        $input['search_all']    = get_proc_data($input,"search_all");

        //収録曲変更　現在の収録曲
        $playlist_detail = Playlist::getPlaylist_detail($input['id']);
        $playlist = null;
        //楽曲検索
        $input['mus_name_asc'] = true;
        $music = Music::getMusic_list(10,true,$input['page'],$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄ

        $msg = request('msg');
        $msg = ($msg===NULL && $input['search_all'] !==null && $music === null) ? "検索結果が0件です。" : $msg;

        return view('admin.admin_home', compact('playlist_detail', 'playlist', 'music', 'input', 'msg'));
    }
    //詳細変更用　関数(変更・削除・追加)
    public function playlist_chg_detail_fnc(Request $request)
    {
        make_error_log("album_chg_detail_fnc.log","-----start-----");
        $input = $request->all();
        $msg=null;
        make_error_log("album_chg_detail_fnc.log","fnc=".$input['fnc']." pl_id=".$input['id']." detail_id=".$input['detail_id']);
        
        $ret = Playlist::chgPlaylist_detail($input);
        switch($input['fnc']){
            case "del":
                if($ret['error_code']==-1)    $msg = "収録曲の削除に失敗しました。";
                if($ret['error_code']==0)    $msg = "収録曲を削除しました。";
                break;
            case "add":
                if($ret['error_code']==-1)    $msg = "ﾌﾟﾚｲﾘｽﾄへの追加に失敗しました。";
                if($ret['error_code']==0)    $msg = "ﾌﾟﾚｲﾘｽﾄに追加しました。";
                break;
            default:
        }
        //return redirect()->route('admin-playlist-chgdetail', ['input' => $input, 'msg' => $msg]);
        return redirect()->route('admin-playlist-detail-search', ['input' => $input, 'msg' => $msg]);
        
    }
}
