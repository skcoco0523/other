<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Playlist;
use App\Models\CustomCategory;

class FavoriteController extends Controller
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

    //お気に入り表示
    public function favorite_show(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['page']))              $input['page']=null;
        if (empty($input['table']))             $input['table']='all';
        if (empty($input['bit_num']))           $input['bit_num']=null;
        //選択しているタブのﾍﾟｰｼﾞｬｰのみページを指定する
        $mus_page = ($input['table'] == "mus")       ? $input['page'] :1; //曲
        $alb_page = ($input['table'] == "alb")       ? $input['page'] :1; //アルバム
        $pl_page  = ($input['table'] == "pl")        ? $input['page'] :1; //プレイリスト
        $pl_page  = ($input['table'] == "mypl")      ? $input['page'] :1; //myプレイリスト
        $cc_page  = ($input['table'] == "category")  ? $input['page'] :1; //カテゴリ別>選択カテゴリ
        $favorite_list = array();
        $custom_category_list = array();

        //すべてタブ、曲タブ
        if($input['table']=='all' || $input['table']=="mus"){
            $favorite_list["mus"]       = Favorite::getFavorite(10,true,$mus_page,Auth::id(),"mus");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
        }
        //すべてタブ、アルバムタブ
        if($input['table']=='all' || $input['table']=="alb"){
            $favorite_list["alb"]       = Favorite::getFavorite(10,true,$alb_page,Auth::id(),"alb");  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
        }
        //すべてタブ、プレイリストタブ
        if($input['table']=='all' || $input['table']=="pl"){
            $favorite_list["pl"]        = Favorite::getFavorite(10,true,$pl_page ,Auth::id(),"pl" );  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,table
        }
        //マイプレイリストタブ
        if($input['table']=="mypl"){
            $favorite_list["mypl"]      = Playlist::getPlaylist_list(10,true,$pl_page,['user_id' => true],);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        }
        //カテゴリ別タブ
        if($input['table']=="category"){
            $custom_category_list       = CustomCategory::getCustomCategory(null,false,null,null);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,ビット番号
            $favorite_list["category"]  = CustomCategory::getCustomCategory(10,true,$cc_page ,Auth::id(),$input['bit_num']);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,ビット番号
        }
        //dd($input['table'],$favorite_list);
        //dd($favorite_list,$custom_category_list,$input);
        $msg = null;
        if($favorite_list || $custom_category_list){
            return view('favorite_show', compact('favorite_list', 'custom_category_list', 'input', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のデータが存在しません');
        }
    }
    //お気に入り登録
    public function favorite_chg(Request $request)
    {
        $table = $request->input('table');
        $detail_id = $request->input('detail_id');
        $type = $request->input('type');    //add or del
        $ret = Favorite::chgFavorite(Auth::id(),$table,$detail_id,$type);
        
        return $ret;
    }
}
