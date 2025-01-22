<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomCategoryDefine extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'bit_num', 'sort_num'];
    protected $table = 'custom_categories_define';


    //取得
    public static function getCustomCategory_list()
    {
        try {
            //カテゴリ一覧を取得
            $sql_cmd = DB::table('custom_categories_define');
            $sql_cmd = $sql_cmd->orderBy('sort_num', 'asc')->get();
            
            $custom_category = $sql_cmd;
            return $custom_category; 
        } catch (\Exception $e) {
            make_error_log("getCustomCategory_list.log","failure");
            return null; 
        }
    }

    //作成
    public static function createCustomCategory($data)
    {
        make_error_log("createCustomCategory.log","---------start----------");
        try {
            
            if(!$data['name'])      return ['error_code' => 1];   //データ不足
            //既存表示順(最後尾)を取得
            $data['sort_num']   = DB::table('custom_categories_define')->max('sort_num');
            if($data['sort_num']==null)     $data['sort_num']=1;
            else                            $data['sort_num']++;
            
            //新規bit番号を取得
            $data['bit_num']    = DB::table('custom_categories_define')->max('bit_num');
            if($data['bit_num']==null)      $data['bit_num'] = 1;
            else                            $data['bit_num'] = $data['bit_num'] * 2;
            make_error_log("createCustomCategory.log","data=".print_r($data,1));
            $result = self::create($data);
            make_error_log("createCustomCategory.log","success");
            return ['error_code' => 0];   //追加成功
        } catch (\Exception $e) {
            make_error_log("createCustomCategory.log","failure");
            return ['error_code' => -1];   //追加失敗
        }
    }
    
    //変更
    public static function chgCustomCategory($data)
    {
        make_error_log("chgCustomCategory.log","---------start----------");
        try {
            if(!$data['id'])    return ['error_code' => 1];   //データ不足
            if(!$data['name'])  return ['error_code' => 2];   //データ不足
            //DB追加処理チェック
            make_error_log("chgCustomCategory.log","data=".print_r($data,1));

            CustomCategoryDefine::where('id', $data['id'])
                ->update(['name' => $data['name'],'disp_flag' => $data['disp_flag']]);

            make_error_log("chgCustomCategory.log","success");
            return ['error_code' => 0];   //追加成功
        } catch (\Exception $e) {
            make_error_log("chgCustomCategory.log","failure");
            return ['error_code' => -1];   //追加失敗
        }
    }
    
    //カカテゴリ表示順変更
    public static function chgsortCustomCategory($data)
    {
        make_error_log("chgsortCustomCategory.log","---------start----------");
        try {
            if(!$data['fnc'])           return ['error_code' => 1];   //データ不足
            if(!$data['id'])            return ['error_code' => 1];   //データ不足

            make_error_log("chgsortCustomCategory.log","sort_chg id=".$data['id']."-".$data['fnc']);
            
            $sort1=DB::table('custom_categories_define')->where('id', $data['id'])->value('sort_num');
            
            if($data['fnc']=="up"){
                $sort2=$sort1-1;
                //0以下は不可
                if($sort2<=0)           return ['error_code' => -1];   //削除失敗
            }elseif($data['fnc']=="down"){
                $sort2=$sort1+1;
                $max_num=DB::table('custom_categories_define')->max('sort_num');
                //連番にするため最大値以上との入れ替えを禁止
                if($sort2>$max_num)     return ['error_code' => -1];   //削除失敗
            }
            //表示順を入れ替え
            $replace_id = DB::table('custom_categories_define')->where('sort_num', $sort2)->value('id');

            CustomCategoryDefine::where('id', $data['id'])->update(['sort_num' => $sort2]);
            CustomCategoryDefine::where('id', $replace_id)->update(['sort_num' => $sort1]);

            make_error_log("chgsortCustomCategory.log","success");
            return ['error_code' => 0];   //削除成功
        } catch (\Exception $e) {
            make_error_log("chgsortCustomCategory.log","failure");
            return ['error_code' => -1];   //削除失敗
        }

    }
}
