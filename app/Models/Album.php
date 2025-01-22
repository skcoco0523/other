<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Music;
use App\Models\Favorite;

class Album extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'art_id', 'release_date', 'aff_id'];     //一括代入の許可
    //アルバム一覧取得
    public static function getAlbum_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null,$art_id=null)
    {
        $sql_cmd = DB::table('albums');
        $sql_cmd = $sql_cmd->join('artists', 'albums.art_id', '=', 'artists.id');
        $sql_cmd = $sql_cmd->leftJoin('musics', 'albums.id', '=', 'musics.alb_id');
        if($keyword){

            //管理者による検索
            if(get_proc_data($keyword,"admin_flag")){
                //全検索
                if(get_proc_data($keyword,"search_all")) {
                    $sql_cmd = $sql_cmd->Where('albums.name', 'like', '%'. $keyword['search_all']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name', 'like', '%'. $keyword['search_all']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name2', 'like', '%'. $keyword['search_all']. '%');

                }else{
                    if(get_proc_data($keyword,"search_album"))
                        $sql_cmd = $sql_cmd->where('albums.name', 'like', '%'. $keyword['search_album']. '%');
        
                    if(get_proc_data($keyword,"search_artist")) {
                        $sql_cmd = $sql_cmd->where('artists.name', 'like', '%'. $keyword['search_artist']. '%');
                        $sql_cmd = $sql_cmd->orwhere('artists.name2', 'like', '%'. $keyword['search_artist']. '%');
                    }
                }
            //ユーザーによる検索
            }else{
                if(get_proc_data($keyword,"keyword")) {
                    $sql_cmd = $sql_cmd->Where('albums.name', 'like', '%'. $keyword['keyword']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name', 'like', '%'. $keyword['keyword']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name2', 'like', '%'. $keyword['keyword']. '%');
                }
                $sql_cmd = $sql_cmd->orderBy('albums.name','asc');
            }
            //並び順
            if(get_proc_data($keyword,"alb_name_asc"))  $sql_cmd = $sql_cmd->orderBy('albums.name',         'asc');
            if(get_proc_data($keyword,"art_name_asc"))  $sql_cmd = $sql_cmd->orderBy('artists.name',        'asc');
            if(get_proc_data($keyword,"mus_cnt_asc"))   $sql_cmd = $sql_cmd->orderBy('mus_cnt',             'asc');
            if(get_proc_data($keyword,"release_asc"))   $sql_cmd = $sql_cmd->orderBy('albums.release_date', 'asc');
            if(get_proc_data($keyword,"cdate_asc"))     $sql_cmd = $sql_cmd->orderBy('albums.created_at',   'asc');
            if(get_proc_data($keyword,"udate_asc"))     $sql_cmd = $sql_cmd->orderBy('albums.updated_at',   'asc');
            
            if(get_proc_data($keyword,"alb_name_desc")) $sql_cmd = $sql_cmd->orderBy('albums.name',         'desc');
            if(get_proc_data($keyword,"art_name_desc")) $sql_cmd = $sql_cmd->orderBy('artists.name',        'desc');
            if(get_proc_data($keyword,"mus_cnt_desc"))  $sql_cmd = $sql_cmd->orderBy('mus_cnt',             'desc');
            if(get_proc_data($keyword,"release_desc"))  $sql_cmd = $sql_cmd->orderBy('albums.release_date', 'desc');
            if(get_proc_data($keyword,"cdate_desc"))    $sql_cmd = $sql_cmd->orderBy('albums.created_at',   'desc');
            if(get_proc_data($keyword,"udate_desc"))    $sql_cmd = $sql_cmd->orderBy('albums.updated_at',   'desc');
        }
        if($art_id){
            $sql_cmd = $sql_cmd->where('albums.art_id', '=', $art_id);
        }
        //カラム追加、取得データを追加する場合はgroupbyにも追加する
        $sql_cmd = $sql_cmd->select('albums.id','albums.name','albums.art_id','albums.release_date','albums.aff_id','albums.created_at','albums.updated_at',
                                    'artists.name as art_name', 'albums.id as mus_id', 
                                    'albums.name as alb_name', 'albums.aff_id as alb_aff_id', DB::raw('COUNT(musics.id) as mus_cnt'));
        $sql_cmd = $sql_cmd->groupBy('albums.id','albums.name','albums.art_id','albums.release_date','albums.aff_id','albums.created_at','albums.updated_at',
                                    'artists.name', 'albums.id',
                                    'albums.name', 'albums.aff_id');
                                    
        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $album = $sql_cmd;

        //収録数、収録曲を取得
        foreach ($album as $alb) {
            $music_list = DB::table('musics')->where('alb_id', $alb->id)->select('name')->get();
            $alb->music_list = "";
            //$alb->mus_cnt = 0;
            foreach ($music_list as $music) {
                $alb->music_list = $alb->music_list. $music->name. "\r\n";
                //$alb->mus_cnt++ ;
            }
        }
        //アーティスト名を取得
        foreach ($album as $alb) {
            $alb->art_name = DB::table('artists')->where('id', $alb->art_id)->first()->name;
            //ログインしているユーザーはお気に入り情報も取得する
            if (Auth::check())      $alb->fav_flag = Favorite::chkFavorite(Auth::id(), "alb", $alb->id);
            else                    $alb->fav_flag = 0;
        }

        //画像情報を付与
        $album=setAffData($album);
        return $album; 
    }
    //取得
    public static function getAlbum_detail($alb_id,$keyword=null)
    {
        try {
            
            //アルバム情報を取得
            $sql_cmd = DB::table('albums');;
            $sql_cmd->join('artists', 'albums.art_id', '=', 'artists.id');
            //$sql_cmd->leftJoin('musics', 'albums.id', '=', 'musics.alb_id');
            $sql_cmd->select('albums.*','albums.id as alb_id','artists.name as art_name');
            
            //配列で受け取った場合一括で取得する
            if (is_array($alb_id)) {
                $sql_cmd->whereIn('albums.id', $alb_id);
            } else {
                $sql_cmd->where('albums.id', $alb_id);
            }
                
            if($keyword){  
                if (get_proc_data($keyword,"alb_name_asc")){
                    $sql_cmd->orderBy('albums.name','asc');
                }
                if (get_proc_data($keyword,"default_sort")){
                    // もとの順序で並べる
                    $sql_cmd->orderByRaw('FIELD(albums.id, ' . implode(',', $alb_id) . ')');
                }
            }

            $album = $sql_cmd->get();

            foreach ($album as $alb) {
                // 収録曲の詳細情報を取得
                $music = DB::table('musics')->where('alb_id', $alb->alb_id)->get();
                
                $id_list = array();
                foreach($music as $mus){
                    //$ranking[] = Music::getMusic_detail($fav->fav_id);
                    $id_list[] = $mus->id;
                }
                $input['default_sort'] = true;  //元の順番で取得する
                $detail_list = Music::getMusic_detail($id_list,$input);

                $alb->music = $detail_list;
                $alb->mus_cnt = count($detail_list);
            
                // ログインしているユーザーはお気に入り情報も取得する
                $alb->fav_flag = Auth::check() ? Favorite::chkFavorite(Auth::id(), "alb", $alb->id) : 0;
                
                //画像情報を付与
                $alb=setAffData($alb);
            }
            if (is_array($alb_id))   return $album; 
            else                    return $album[0]; 

        } catch (\Exception $e) {
            make_error_log("getAlbum_detail.log","failure");
            return null; 
        }
    }
    //作成
    public static function createAlbum($data)
    {
        make_error_log("createAlbum.log","-------start-------");
        make_error_log("createAlbum.log","name=".$data['name']." art_id=".$data['art_id']." release_date=".$data['release_date']." aff_id=".$data['aff_id']);

        //データチェック
        if(!isset($data['name']) || !$data['name'])     return ['id' => null, 'error_code' => 1];   //データ不足
        if(!isset($data['art_id']) || !$data['art_id']) return ['id' => null, 'error_code' => 2];   //データ不足
        //if(!isset($data['aff_id']) || !$data['aff_id']) return ['id' => null, 'error_code' => 3];   //データ不足
        
        //DB追加処理チェック
        try {
            // DBに追加
            $Album = self::create($data);
            $alb_id = $Album->id;
            make_error_log("createAlbum.log","success");
            return ['id' => $alb_id, 'error_code' => 0];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createAlbum.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加成功
        }
    }
    //変更
    public static function chgAlbum($data)
    {
        //データチェック
        if(!isset($data['id']) || !$data['id'])         return ['id' => null, 'error_code' => 1];   //データ不足
        if(!isset($data['art_id']) || !$data['art_id']) return ['id' => null, 'error_code' => 2];   //データ不足
        if(!isset($data['name']) || !$data['name'])     return ['id' => null, 'error_code' => 3];   //データ不足
        
        make_error_log("chgAlbum.log","-------start-------");
        make_error_log("chgAlbum.log","id=".$data['id']." art_id=".$data['art_id']." name=".$data['name']);
        
        
        $album = DB::table('albums')->where('id', $data['id'])->first();
        if ($album !== null) {
            
            // 更新対象となるカラムと値を連想配列に追加
            $updateData = [];
            if(isset($data['name']))            $updateData['name']             = $data['name'];
            if(isset($data['art_id']))          $updateData['art_id']           = $data['art_id'];
            if(isset($data['release_date']))    $updateData['release_date']     = $data['release_date'];
            //aff_idなしから新規でaff_idを作成する場合
            if(isset($data['aff_id']))          $updateData['aff_id']           = $data['aff_id'];
        
            make_error_log("chgAlbum.log","after_data=".print_r($updateData,1));
            
            Album::where('id', $data['id'])->update($updateData);

            //アーティストが変更された場合、曲のart_idも更新する
            if($album->art_id != $data['art_id']){
                make_error_log("chgAlbum.log","change:musics.art_id");
                DB::table('musics')->where('alb_id', $data['id'])->update(['art_id' => $data['art_id']]);
            }


            return ['id' => $data['id'], 'error_code' => 0];   //更新成功
        } else {
            return ['id' => null, 'error_code' => -1];   //更新失敗
        }
    }
    //削除：アルバム・アフィリエイトリンク・収録曲　を削除                    ●●●●●●●●●戻り値にエラーコードを返して、呼び出しもとでエラーメッセージを定義●●●●●●●●
    public static function delAlbum($id)
    {
        $msg="";
        //データチェック
        $musics = DB::table('musics')->where('alb_id', $id)->get();
        $albums = DB::table('albums')->where('id', $id)->first();
        
        if ($albums !== null) {
            make_error_log("delAlbum.log","-------start-------");
            //削除対象アルバムの収録曲削除
            foreach ($musics as $music) {
                make_error_log("delAlbum.log","mus_id=".$music->id);
                DB::table('musics')->where('id', $music->id)->delete();
            }
            DB::table('albums')->where('id', $albums->id)->delete();
            if( $albums->aff_id){
                DB::table('affiliates')->where('id', $albums->aff_id)->delete();
            }
            make_error_log("delAlbum.log","alb_id=".$albums->id, " alb_id=".$albums->id, " alb_name=".$albums->name, " alb_aff_id=".$albums->aff_id);

            $msg = "アルバム：{$albums->name}、収録曲を削除しました。";
        } else {
            $msg = "指定されたアルバムは存在しません。";
        }
        return $msg;
    }
}
