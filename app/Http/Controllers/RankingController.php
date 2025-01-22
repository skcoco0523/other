<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ranking;
use App\Models\CustomCategory;
use App\Models\CustomCategoryDefine;

class RankingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //ゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //お気に入りランキング
    public function favorite_ranking(Request $request)
    {
        $page   = $request->input('page', 1);
        $table  = $request->input('table'); //mus,art,alb,pl
        $fav_ranking = Ranking::getFavoriteRanking(10,true,$page,$table);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ﾃｰﾌﾞﾙ
        //dd($fav_ranking);
        $msg = null;
        //dd($music);
        if($table=='mus') $fav_ranking->name = "お気に入り数順：曲";
        if($table=='art') $fav_ranking->name = "お気に入り数順：アーティスト";
        if($table=='alb') $fav_ranking->name = "お気に入り数順：アルバム";
        if($table=='pl')  $fav_ranking->name = "お気に入り数順：プレイリスト";
        if($fav_ranking)  $fav_ranking->table = $table;

        //
        if($fav_ranking && !($fav_ranking->isEmpty())){
            return view('favorite_ranking_show', compact('fav_ranking', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のデータが存在しません');
        }
    }
    //カテゴリ別ランキング
    public function category_ranking(Request $request)
    {
        $page   = $request->input('page', 1);
        $id  = $request->input('id'); //カテゴリid
        if($id){
            //カテゴリ　項目のみ
            $category_list = CustomCategory::getCustomCategory(null,false,null,null,null);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,user_id,ビット番号
            $bit_num = 0;
            foreach($category_list as $category){
                if($category->id == $id && $category->disp_flag == 1) {
                    $bit_num = $category->bit_num;
                    $category_name = $category->name;
                }
            }
            //対象カテゴリがあれば処理続行
            $category_ranking = null;
            $msg = null;
            if($bit_num){
                $category_ranking = Ranking::getCategoryRanking(10,true,$page,$bit_num);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ビット番号
                $category_ranking->name = "カテゴリ別ランキング：". $category_name;
            }
            return view('category_ranking_show', compact('category_ranking', 'id', 'msg'));
        }
        return redirect()->route('home')->with('error', '該当のデータが存在しません');

    }
}
