<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \App\Models\Favorite;
use \App\Models\Music;
use \App\Models\Album;
use \App\Models\Playlist;
use Carbon\Carbon;


class Favorite extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'fav_id'];     //一括代入の許可


    //お気に入り情報取得
    public static function getFavorite($disp_cnt=null,$pageing=false,$page=1,$user_id, $table)
    {
        try {

            // table: mus alb pl 引数のテーブルに合わせて取得
            $sql_cmd = DB::table("favorite_$table")->where('user_id', $user_id);

            //無駄な接続にはなるけどjoinして
            //$sql_cmd = $sql_cmd->orderBy('musics.name','asc');
            //で並べ替えてから取得
            
            // ページング・取得件数指定・全件で分岐
            if ($pageing){
                if ($disp_cnt === null) $disp_cnt=5;
                $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
            }                       
            elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
            else                                $sql_cmd = $sql_cmd->get();

            $favorite = $sql_cmd;

            $favorite_detail = null;
            foreach($favorite as $fav){
                //$ranking[] = Music::getMusic_detail($fav->fav_id);
                $id_list[] = $fav->fav_id;
            }
            if($table=="mus"){
                $input['mus_name_asc'] = true;
                $favorite_detail = Music::getMusic_detail($id_list,$input);
            }
            if($table=="alb"){
                $input['alb_name_asc'] = true;
                $favorite_detail = Album::getAlbum_detail($id_list,$input);
            }
            if($table=="pl"){
                $input['pl_name_asc'] = true;
                $favorite_detail = Playlist::getPlaylist_detail($id_list,$input);
            }
            //ページングでは戻り値を加工する
            if ($pageing) {
                //ﾍﾟｰｼﾞｬｰ情報も合わせて返す
                return $favorite->setCollection(collect($favorite_detail));
            } else {
                return $favorite_detail;
            }

        } catch (\Exception $e) {
            make_error_log("getFavorite.log","failure");
            return null;
        }
    }
    //お気に入りチェック
    public static function chkFavorite($user_id, $table, $fav_id)
    {
        try {
            $where = ["user_id"=>$user_id, "fav_id"=>$fav_id];
            //第二引数でテーブルを指定
            if($table === "mus") $favorite = DB::table('favorite_mus')->where($where)->first();
            if($table === "alb") $favorite = DB::table('favorite_alb')->where($where)->first();
            if($table === "pl") $favorite = DB::table('favorite_pl')->where($where)->first();

            return ($favorite) ? 1 : 0;
        } catch (\Exception $e) {
            make_error_log("chkFavorite.log","failure");
            return null;
        }
    }

    //変更
    public static function chgFavorite($user_id, $table, $fav_id, $type)//type:add=追加、del=削除
    {
        make_error_log("chgFavorite.log","-------start-------");
        //make_error_log("favorite_chg","detail_id=".print_r($detail_id,1));
        try {
            make_error_log("chgFavorite.log","user_id=".$user_id." table=".$table." fav_id=".$fav_id." type=".$type);
            $fav_chk = Favorite::chkFavorite($user_id, $table, $fav_id);
            make_error_log("chgFavorite.log","fav_chk=".$fav_chk);
            $data = ["user_id"=>$user_id, "fav_id"=>$fav_id];

            //変更対象のテーブルを定義
            if($table=="mus")$table="favorite_mus";
            if($table=="alb")$table="favorite_alb";
            if($table=="pl")$table="favorite_pl";

            if($type=="add"){
                if($fav_chk){
                    //追加済み
                    make_error_log("chgFavorite.log","already added");
                    return "すでに登録済みです。";
                }else{
                    //追加処理
                    make_error_log("chgFavorite.log","to add");
                    //self::create($data);
                    // 現在の日時を設定
                    $data['created_at'] = Carbon::now();
                    $data['updated_at'] = Carbon::now();
                    DB::table($table)->insert($data);
                    //create($validatedData);
                    return "add";
                }
            }elseif($type=="del"){
                if($fav_chk){
                    //削除
                    make_error_log("chgFavorite.log","delete");
                    DB::table($table)->where($data)->delete();
                    //self::where($data)->delete();
                    return "del";
                }else{
                    //削除対象無し
                    make_error_log("chgFavorite.log","not match");
                    return "お気に入り解除済みです。";
                }
            }else{
                return "更新に失敗しました。";   //更新失敗
            }

        } catch (\Exception $e) {
            make_error_log("chgFavorite.log","failure");
            return "更新に失敗しました。";   //更新失敗
        }
    }
}
