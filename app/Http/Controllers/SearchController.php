<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Search;
use App\Models\Artist;
use App\Models\Music;
use App\Models\Album;
use App\Models\Playlist;
use App\Models\SearchHistory;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //検索はゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //検索
    public function search_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['keyword']))           $input['keyword']=null;

        //ログインしているユーザーは検索履歴を表示
        if(Auth::check()){
            $history = SearchHistory::getSearchHistory(20);
        }else{
            $history = collect();
        }
        //dd($history);
        $msg = null;
        
        return view('search_show', compact('history','input', 'msg'));
    }
    //検索結果一覧 
    public function search_list_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $input['page']              = get_proc_data($input,"page");
        $input['keyword']           = get_proc_data($input,"keyword");
        $input['table']             = get_proc_data($input,"table");

        if (!$input['table'])         $input['table']='all';
        //選択しているタブのﾍﾟｰｼﾞｬｰのみページを指定する
        $art_page = ($input['table'] == "art") ? $input['page'] :1;
        $mus_page = ($input['table'] == "mus") ? $input['page'] :1;
        $alb_page = ($input['table'] == "alb") ? $input['page'] :1;
        $pl_page  = ($input['table'] == "pl")  ? $input['page'] :1;

        //$table = ["art","mus","alb","pl"];
        
        //すべてタブ、曲タブ
        if($input['table']=='all' || $input['table']=="art"){
            $search_list["art"] = Artist::getArtist_list(10,true,$art_page,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        }        
        //すべてタブ、曲タブ
        if($input['table']=='all' || $input['table']=="mus"){
            $search_list["mus"] = Music::getMusic_list(10,true,$mus_page,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        }        
        //すべてタブ、曲タブ
        if($input['table']=='all' || $input['table']=="alb"){
            $search_list["alb"] = Album::getAlbum_list(10,true,$alb_page,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        }        
        //すべてタブ、曲タブ
        if($input['table']=='all' || $input['table']=="pl"){
            $search_list["pl"]  = Playlist::getPlaylist_list(10,true,$pl_page,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        }
        //dd($search_list["pl"]);
        //検索履歴の登録
        if($input['keyword']) SearchHistory::createSearchHistory($input['keyword']);
        $msg = null;
        //dd($search_list);
        
        return view('search_list_show', compact('search_list','input', 'msg'));
    }
    //検索結果補足一覧取得
    public function search_suggestions(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        $input['keyword']           = get_proc_data($input,"keyword");
        
        //$table = ["art","mus","alb","pl"];
        $search_list["art"] = Artist::getArtist_list(10,false,1,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["mus"] = Music::getMusic_list(10,false,1,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["alb"] = Album::getAlbum_list(10,false,1,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $search_list["pl"] = Playlist::getPlaylist_list(10,false,1,$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ     

        $suggestions=array();
        foreach($search_list as $list){
            foreach($list as $data){
                $suggestions[] = $data->name;
            }
        }
        $suggestions = array_unique($suggestions);

        //検索補足データを返す
        return response()->json($suggestions);
    }
    //検索履歴削除
    public function del_search_history(Request $request)
    {
        //ログインしているユーザーは検索履歴を表示
        $this->middleware('auth');
        $ret = SearchHistory::delSearchHistory();
        return $ret;
    }
}
