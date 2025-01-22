<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Music;

class Ranking extends Model
{
    use HasFactory;
    
    //お気に入りランキング取得
    public static function getFavoriteRanking($disp_cnt=null,$pageing=false,$page=1,$table=null) {

        if($table){
            if    ($table=="mus") $sql_cmd = DB::table('favorite_mus');
            elseif($table=="alb") $sql_cmd = DB::table('favorite_alb');
            elseif($table=="pl")  $sql_cmd = DB::table('favorite_pl');
            //ユーザーがtableを修正して引き渡した場合の対応
            else return null;

            $sql_cmd->select('fav_id', DB::raw('COUNT(*) as count'));
            $sql_cmd->groupBy('fav_id')->orderByDesc('count');

            // ページング・取得件数指定・全件で分岐
            if ($pageing){
                if ($disp_cnt === null) $disp_cnt=5;
                $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
            }                       
            elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
            else                                $sql_cmd = $sql_cmd->get();       
            $favorite = $sql_cmd;

            $id_list = array();
            foreach($favorite as $fav){
                //$ranking[] = Music::getMusic_detail($fav->fav_id);
                $id_list[] = $fav->fav_id;
            }
            
            $ranking = array();
            $input['default_sort'] = true;  //元の順番で取得する
            $ranking = Music::getMusic_detail($id_list,$input);
            if($table=="mus")  $ranking = Music::getMusic_detail($id_list);
            if($table=="alb") {}
            if($table=="pl") {}

            if($ranking){
                //一致するアイテムを検索
                $ranking = collect($ranking)->sortBy(function($item) use ($favorite) {
                    return $favorite->search(function($fav) use ($item) {
                        return $fav->fav_id == $item->mus_id;
                    });
                });
                if($pageing){
                    $favorite->setCollection(collect($ranking));
                }else{
                    //$favorite = $favorite->replace($ranking);
                    $favorite = $ranking;
                    //dd($recommend2,$detail);
                }
            }
            //dd($favorite);
            return $favorite;
        }
        return null;
    }

    //カテゴリ別ランキング取得
    public static function getCategoryRanking($disp_cnt=null,$pageing=false,$page=1,$bit_num=null) {

        if($bit_num){
            //選択カテゴリのデータを取得
            $sql_cmd = DB::table('custom_categories');
            $sql_cmd = $sql_cmd->leftJoin('musics', 'musics.id', '=', 'custom_categories.music_id');
            $sql_cmd = $sql_cmd->whereRaw("category_bit & ? > 0", [$bit_num]);
            //カラム追加、取得データを追加する場合はgroupbyにも追加する
            $sql_cmd = $sql_cmd->select('musics.id', DB::raw('COUNT(musics.id) as mus_cnt'));
            $sql_cmd = $sql_cmd->groupBy('musics.id');
            $sql_cmd = $sql_cmd->orderBy('mus_cnt', 'desc');
    
            // ページング・取得件数指定・全件で分岐
            if ($pageing){
                if ($disp_cnt === null) $disp_cnt=5;
                $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
            }                       
            elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
            else                                $sql_cmd = $sql_cmd->get();
            $music_list = $sql_cmd;

            $id_list = array();
            foreach($music_list as $key => $mus){
                //$ranking[$key] = Music::getMusic_detail($mus->id);
                $id_list[] = $mus->id;
            }
            $input['default_sort'] = true;  //元の順番で取得する
            $ranking = Music::getMusic_detail($id_list,$input);

            if($ranking){
                if($pageing){
                    $music_list->setCollection(collect($ranking));
                }else{
                    $music_list = $ranking->replace($ranking);
                    //dd($recommend2,$detail);
                }
            }
            //dd($favorite);
            return $music_list;
        }
        return null;
    }
}

