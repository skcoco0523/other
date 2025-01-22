<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class Music extends Model
{
    use HasFactory;
    protected $table = 'musics';    //musicテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['alb_id', 'art_id', 'name', 'release_date', 'link', 'aff_id'];     //一括代入の許可

    //楽曲一覧取得
    public static function getMusic_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null,$art_id=null)
    {
        $sql_cmd = DB::table('musics');
        $sql_cmd = $sql_cmd->join('artists', 'musics.art_id', '=', 'artists.id');
        $sql_cmd = $sql_cmd->leftJoin('albums', 'musics.alb_id', '=', 'albums.id');
        
        if($keyword){

            //管理者による検索
            if(get_proc_data($keyword,"admin_flag")){
                if (get_proc_data($keyword,"search_all")) {
                    $sql_cmd = $sql_cmd->orwhere('musics.name', 'like', '%'. $keyword['search_all']. '%');
                    $sql_cmd = $sql_cmd->orwhere('artists.name', 'like', '%'. $keyword['search_all']. '%');
                    $sql_cmd = $sql_cmd->orwhere('artists.name2', 'like', '%'. $keyword['search_all']. '%');
                    $sql_cmd = $sql_cmd->orwhere('albums.name', 'like', '%'. $keyword['search_all']. '%');
    
                }else{
                    if (get_proc_data($keyword,"search_music")){
                        $sql_cmd = $sql_cmd->where('musics.name', 'like', '%'. $keyword['search_music']. '%');
                    }
                    if (get_proc_data($keyword,"search_artist")){
                        $sql_cmd = $sql_cmd->where('artists.name', 'like', '%'. $keyword['search_artist']. '%');
                        $sql_cmd = $sql_cmd->orwhere('artists.name2', 'like', '%'. $keyword['search_artist']. '%');
                    }
    
                    if (get_proc_data($keyword,"search_album")){
                        $sql_cmd = $sql_cmd->where('albums.name', 'like', '%'. $keyword['search_album']. '%');
                    }
                }

            //ユーザーによる検索
            }else{
                if (get_proc_data($keyword,"keyword")){
                    $sql_cmd = $sql_cmd->where('musics.name', 'like', '%'. $keyword['keyword']. '%');
                    $sql_cmd = $sql_cmd->orwhere('artists.name', 'like', '%'. $keyword['keyword']. '%');
                    $sql_cmd = $sql_cmd->orwhere('artists.name2', 'like', '%'. $keyword['keyword']. '%');
                }
                $sql_cmd = $sql_cmd->orderBy('musics.name','asc');
            }
            //並び順
            if(get_proc_data($keyword,"mus_name_asc"))  $sql_cmd = $sql_cmd->orderBy('musics.name',         'asc');
            if(get_proc_data($keyword,"art_name_asc"))  $sql_cmd = $sql_cmd->orderBy('artists.name',        'asc');
            if(get_proc_data($keyword,"alb_name_asc"))  $sql_cmd = $sql_cmd->orderBy('albums.name',         'asc');
            if(get_proc_data($keyword,"release_asc"))   $sql_cmd = $sql_cmd->orderBy('musics.release_date', 'asc');
            if(get_proc_data($keyword,"cdate_asc"))     $sql_cmd = $sql_cmd->orderBy('musics.created_at',   'asc');
            if(get_proc_data($keyword,"udate_asc"))     $sql_cmd = $sql_cmd->orderBy('musics.updated_at',   'asc');
            
            if(get_proc_data($keyword,"mus_name_desc")) $sql_cmd = $sql_cmd->orderBy('musics.name',         'desc');
            if(get_proc_data($keyword,"art_name_desc")) $sql_cmd = $sql_cmd->orderBy('artists.name',        'desc');
            if(get_proc_data($keyword,"alb_name_desc")) $sql_cmd = $sql_cmd->orderBy('albums.name',         'desc');
            if(get_proc_data($keyword,"release_desc"))  $sql_cmd = $sql_cmd->orderBy('musics.release_date', 'desc');
            if(get_proc_data($keyword,"cdate_desc"))    $sql_cmd = $sql_cmd->orderBy('musics.created_at',   'desc');
            if(get_proc_data($keyword,"udate_desc"))    $sql_cmd = $sql_cmd->orderBy('musics.updated_at',   'desc');
  
        }
        if($art_id){
            $sql_cmd = $sql_cmd->where('musics.art_id', '=', $art_id);
            $sql_cmd = $sql_cmd->orderBy('musics.name','asc');
        }
        
        $sql_cmd = $sql_cmd->select('musics.*', 'artists.name as art_name', 'musics.id as mus_id', 
                                    'albums.name as alb_name', 'albums.aff_id as alb_aff_id');

        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $music = $sql_cmd;
        //dd($music);
        foreach($music as $mus){
            //アフィリエイトIDを書き換える
            if($mus->aff_id == null) $mus->aff_id = $mus->alb_aff_id;
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check())  $mus->fav_flag = Favorite::chkFavorite(Auth::id(), "mus", $mus->mus_id);
            else                $mus->fav_flag = 0;
        }
        
        //画像情報を付与
        $music=setAffData($music);
        return $music; 
    }
    //取得
    public static function getMusic_detail($mus_id,$keyword=null)
    {
        try {
            //オブジェクトの場合と配列の場合の2パターンを作成して負荷軽減
            //楽曲情報を取得
            $sql_cmd = DB::table('musics');
            $sql_cmd ->join('artists', 'musics.art_id', '=', 'artists.id');
            $sql_cmd ->leftJoin('albums', 'musics.alb_id', '=', 'albums.id');
            $sql_cmd ->select(
                    'musics.*',
                    'musics.id as mus_id','musics.name as mus_name',
                    'artists.name as art_name',
                    'albums.name as alb_name','albums.aff_id as album_aff_id','albums.release_date as album_release_date');

            //配列で受け取った場合一括で取得する
            if (is_array($mus_id)) {
                $sql_cmd->whereIn('musics.id', $mus_id);
            } else {
                $sql_cmd->where('musics.id', $mus_id);
            }
                
            if($keyword){  
                if (get_proc_data($keyword,"mus_name_asc")){
                    $sql_cmd->orderBy('musics.name','asc');
                }
                if (get_proc_data($keyword,"default_sort")){
                    // もとの順序で並べる
                    $sql_cmd->orderByRaw('FIELD(musics.id, ' . implode(',', $mus_id) . ')');
                }
                
            }
            $music_list = $sql_cmd->get();

            foreach ($music_list as $music) {
                if (is_null($music->aff_id))        $music->aff_id = $music->album_aff_id;
                if (is_null($music->release_date))  $music->release_date = $music->album_release_date;
                //ログインしているユーザーはお気に入り情報も取得する
                if (Auth::check())  $music->fav_flag = Favorite::chkFavorite(Auth::id(), "mus", $music->mus_id);
                else                $music->fav_flag = 0;
                //dd($music);
                //画像情報を付与
                $music=setAffData($music);
            }

            if (is_array($mus_id))  return $music_list; 
            else                    return $music_list[0]; 
            
        } catch (\Exception $e) {
            return null; 
        }
    }
    //作成
    public static function createMusic($data)
    {
        make_error_log("createMusic.log","-------start-------");
        make_error_log("createMusic.log","create_data=".print_r($data,1));
        //データチェック
        if(!(get_proc_data($data,"name")))        return ['id' => null, 'error_code' => 1];   //データ不足
        if(!(get_proc_data($data,"art_id")))      return ['id' => null, 'error_code' => 2];   //データ不足

        //if(!$data['alb_id'])    return ['id' => null, 'error_code' => 3];   //データ不足      シングルもあるため
        //dd($data);
        //DB追加処理チェック
        //dd($data);
        try {
            // DBに追加
            $music = self::create($data);
            $mus_id = $music->id;
            make_error_log("createMusic.log","success");
            return ['id' => $mus_id, 'error_code' => 0];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createMusic.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
    }
    //削除：曲・アフィリエイトリンク　を削除
    public static function delMusic($id)
    {
        make_error_log("delMusic.log","-------start-------");

        try {
            make_error_log("delMusic.log","delete_mus_id=".$id);
            // musicデータ削除
            $music = DB::table('musics')->where('id', $id)->first();
            DB::table('musics')->where('id', $id)->delete();
            DB::table('affiliates')->where('id', $music->aff_id)->delete();

            make_error_log("delMusic.log","success");
            return ['id' => $music->id, 'error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("delMusic.log","failure");
            return ['id' => null, 'error_code' => -1];   //更新失敗
        }
    }
    //変更
    public static function chgMusic($data)
    {
        make_error_log("chgMusic.log","-------start-------");
        try {
            $music = DB::table('musics')->where('id', $data['id'])->first();
            make_error_log("chgMusic.log","before_data=".print_r($music,1));
            // 更新対象となるカラムと値を連想配列に追加
            $updateData = [];
            if(isset($data['alb_id']))          $updateData['alb_id']        = $data['alb_id'];
            if(isset($data['art_id']))          $updateData['art_id']        = $data['art_id'];
            if(isset($data['name']))            $updateData['name']          = $data['name'];
            if(isset($data['release_date']))    $updateData['release_date']  = $data['release_date'];
            if(isset($data['link']))            $updateData['link']          = $data['link'];
            if(isset($data['aff_id']))          $updateData['aff_id']        = $data['aff_id'];
        
            make_error_log("chgMusic.log","after_data=".print_r($updateData,1));
            // musicデータ更新
            /*  クエリビルダではupdated_atが自動更新されない
            DB::table('musics')->where('id', $updateData['id'])->update($updateData);
            */
            Music::where('id', $data['id'])->update($updateData);

            make_error_log("chgMusic.log","success");
            return ['id' => $music->id, 'error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("chgMusic.log","failure");
            return ['id' => null, 'error_code' => -1];   //更新失敗
        }
    }
}
