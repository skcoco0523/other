<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Artist extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'name2', 'debut', 'sex'];
    //取得
    public static function getArtist_list($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        $sql_cmd = DB::table('artists');
        if($keyword){
            
            //管理者による検索
            if(get_proc_data($keyword,"admin_flag")){
                //全検索
                if (isset($keyword['search_all'])) {
                    $sql_cmd = $sql_cmd->Where('artists.name', 'like', '%'. $keyword['search_all']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name2', 'like', '%'. $keyword['search_all']. '%');

                }else{
                    if (isset($keyword['search_artist'])) {
                        $sql_cmd = $sql_cmd->where('artists.name', 'like', '%'. $keyword['search_artist']. '%');
                        $sql_cmd = $sql_cmd->orwhere('artists.name2', 'like', '%'. $keyword['search_artist']. '%');
                    }
                    if (isset($keyword['search_sex'])) 
                        $sql_cmd = $sql_cmd->where('artists.sex',$keyword['search_sex']);
                }
            //ユーザーによる検索
            }else{
                if (isset($keyword['keyword'])) {
                    $sql_cmd = $sql_cmd->Where('artists.name', 'like', '%'. $keyword['keyword']. '%');
                    $sql_cmd = $sql_cmd->orWhere('artists.name2', 'like', '%'. $keyword['keyword']. '%');
                }
                $sql_cmd = $sql_cmd->orderBy('artists.name','asc');
            }
            //並び順
            if(get_proc_data($keyword,"art_name_asc"))  $sql_cmd = $sql_cmd->orderBy('artists.name',        'asc');
            if(get_proc_data($keyword,"art_name2_asc")) $sql_cmd = $sql_cmd->orderBy('artists.name2',       'asc');
            if(get_proc_data($keyword,"debut_asc"))     $sql_cmd = $sql_cmd->orderBy('artists.debut',       'asc');
            if(get_proc_data($keyword,"cdate_asc"))     $sql_cmd = $sql_cmd->orderBy('artists.created_at',  'asc');
            if(get_proc_data($keyword,"udate_asc"))     $sql_cmd = $sql_cmd->orderBy('artists.updated_at',  'asc');
            
            if(get_proc_data($keyword,"art_name_desc")) $sql_cmd = $sql_cmd->orderBy('artists.name',        'desc');
            if(get_proc_data($keyword,"art_name2_desc"))$sql_cmd = $sql_cmd->orderBy('artists.name2',       'desc');
            if(get_proc_data($keyword,"debut_desc"))    $sql_cmd = $sql_cmd->orderBy('artists.debut',       'desc');
            if(get_proc_data($keyword,"cdate_desc"))    $sql_cmd = $sql_cmd->orderBy('artists.created_at',  'desc');
            if(get_proc_data($keyword,"udate_desc"))    $sql_cmd = $sql_cmd->orderBy('artists.updated_at',  'desc');
        }
        
        // ページング・取得件数指定・全件で分岐
        if ($pageing){
            if ($disp_cnt === null) $disp_cnt=5;
            $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
        }                       
        elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
        else                                $sql_cmd = $sql_cmd->get();

        $artist = $sql_cmd;
        
        // 取得したアーティストidを配列にし、一括でアルバム件数を取得する
        $art_ids = $artist->pluck('id')->toArray();
        $sql_cmd = DB::table('albums');
        $sql_cmd = $sql_cmd->select('art_id', DB::raw('COUNT(*) as alb_cnt'));
        $sql_cmd = $sql_cmd->whereIn('art_id', $art_ids)->groupBy('art_id')->pluck('alb_cnt', 'art_id')->toArray();  
        $alb_cnt = $sql_cmd;

        //アーティストはお気に入り登録なし
        foreach($artist as $art){
            $art->alb_cnt = isset($alb_cnt[$art->id]) ? $alb_cnt[$art->id] : 0;
            $art->fav_flag = 0;
        }

        return $artist; 
    }
    //取得
    public static function getArtist_detail($art_id,$keyword=null)
    {
        try {
            //アルバム情報を取得
            $sql_cmd = DB::table('artists');
            $sql_cmd->select('artists.*','artists.id as art_id','artists.name as art_name');

            //配列で受け取った場合一括で取得する
            if (is_array($art_id)) {
                $sql_cmd->whereIn('artists.id', $art_id);
            } else {
                $sql_cmd->where('artists.id', $art_id);
            }
                
            if($keyword){  
                if (get_proc_data($keyword,"art_name_asc")){
                    $sql_cmd->orderBy('artists.name','asc');
                }
                if (get_proc_data($keyword,"default_sort")){
                    // もとの順序で並べる
                    $sql_cmd->orderByRaw('FIELD(artists.id, ' . implode(',', $mus_id) . ')');
                }
                
            }
            $artist = $sql_cmd->get();

            foreach ($artist as $art) {
                //アーティストは固定でfalse
                if($art)$art->fav_flag = 0;
                //dd($artist);
            }

            if (is_array($art_id))   return $artist; 
            else                    return $artist[0]; 

        } catch (\Exception $e) {
            make_error_log("getArtist_detail.log","failure");
            return null; 
        }
    }
    //作成
    public static function createArtist($data)
    {
        if(!$data['name'])  return 1;   //データ不足
        //DB追加処理チェック
        try {
            $result = self::create($data);
            return 0;   //追加成功
        } catch (\Exception $e) {
            return -1;   //追加失敗
        }
    }
    //変更
    public static function chgArtist($data)
    {
        $msg="";
        //データチェック
        if(!$data['id'])    return "更新対象をテーブルから選択してください。";
        if(!$data['name'])  return "アーティスト名を入力してください。";

        $artist = DB::table('artists')->where('id', $data['id'])->first();
        if ($artist !== null) {
            
            // 更新対象となるカラムと値を連想配列に追加
            $updateData = [];
            if(isset($data['name']))    $updateData['name']     = $data['name'];
            if(isset($data['name2']))   $updateData['name2']    = $data['name2'];
            if(isset($data['debut']))   $updateData['debut']    = $data['debut'];
            if(isset($data['sex']))     $updateData['sex']      = $data['sex'];
        
            make_error_log("chgArtist.log","after_data=".print_r($updateData,1));
            
            Artist::where('id', $data['id'])->update($updateData);

            $msg = "更新しました。";
        } else {
            $msg = "更新に失敗しました。";
        }
        return $msg;
    }
    //削除
    public static function delArtist($id)
    {
        $msg="";
        //データチェック
        $alb_cnt = DB::table('albums')->where('art_id', $id)->count();
        $art_cnt = DB::table('musics')->where('art_id', $id)->count();
        if($alb_cnt)        $msg="アルバム ";
        if($art_cnt)        $msg.="音楽 ";
        if($msg)            $msg.="に紐づいているため削除できません。\n対象を先に削除してください。";
        if($msg!="") return $msg;

        $artist = DB::table('artists')->where('id', $id)->first();
        if ($artist !== null) {
            DB::table('artists')->where('id', $id)->delete();
            $msg = "アーティスト：{$artist->name}を削除しました。";
        } else {
            $msg = "指定されたアーティストは存在しません。";
        }
        return $msg;
    }
}