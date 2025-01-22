<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SearchHistory extends Model
{
    use HasFactory;
    //protected $table = 'search_histories';    //musicテーブルが指定されてしまうため、強制的に指定
    protected $fillable = ['user_id','search_word','del_flag'];     //一括代入の許可
    
    //検索履歴登録
    public static function createSearchHistory($word)
    {
        try {
            $result = self::updateOrCreate(['user_id' => Auth::id(), 'search_word' => $word, 'del_flag' => 0]);
            return $result; 
        } catch (\Exception $e) {
            make_error_log("createSearchHistory.log","failure");
            return null; 
        }
    }
    //検索履歴取得
    public static function getSearchHistory($cnt)
    {
        try {
            $history = DB::table('search_histories')
                        ->where(["user_id"=>Auth::id(), "del_flag" => 0])
                        ->orderBy('created_at', 'desc')
                        ->Limit($cnt)->get();
            return $history; 
        } catch (\Exception $e) {
            make_error_log("getSearchHistory.log","failure");
            return null; 
        }
    }
    //検索履歴削除
    public static function delSearchHistory()
    {
        try {
            $result = self::where('user_id', Auth::id())->update(['del_flag' => 1]);
            return true;
        } catch (\Exception $e) {
            make_error_log("delSearchHistory.log","failure");
            return false; 
        }
    }
}
