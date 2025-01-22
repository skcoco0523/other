<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Music;
use App\Models\CustomCategoryDefine;
//use App\Models\FileTmp;

//カテゴリコントローラー
class AdminCategoryController extends Controller
{
    //カテゴリ設定
    public function custom_category_setting(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $custom_category = CustomCategoryDefine::getCustomCategory_list();
        $msg = request('msg');
        
        return view('admin.admin_home', compact('custom_category', 'input', 'msg'));
    }
    //カテゴリ追加
    public function custom_category_reg(Request $request)
    {
        $input = $request->only(['name']);
        $ret = CustomCategoryDefine::createCustomCategory($input);
        $msg = request('msg');
        
        if($ret['error_code']==0){
            $msg = "カテゴリ：{$input['name']} を追加しました。";
            $input=null;   //データ登録成功時 初期化
        }
        
        if($ret['error_code']==1)   $msg = "カテゴリ名を入力してください。";
        if($ret['error_code']==-1)  $msg = "カテゴリ{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-custom-category-setting', ['input' => $input, 'msg' => $msg]);
    }
    //カテゴリー変更
    public function custom_category_chg(Request $request)
    {
        $input = $request->all();
        $ret = CustomCategoryDefine::chgCustomCategory($input);
        $msg = request('msg');
        
        if($ret['error_code']==0){
            $msg = "カテゴリ：{$input['name']} を更新しました。";
            $input=null;   //データ登録成功時 初期化
        }
        if($ret['error_code']==2)   $msg = "カテゴリ名を入力してください。";
        if($ret['error_code']==-1)  $msg = "カテゴリ{$input['name']} の追加に失敗しました。";
        return redirect()->route('admin-custom-category-setting', ['input' => $input, 'msg' => $msg]);
    }
    //表示順変更用
    public function custom_category_chg_sort(Request $request)
    {
        make_error_log("custom_category_chg_sort.log","-----start-----");
        $input = $request->all();
        //dd($input);
        $msg=null;
        make_error_log("custom_category_chg_sort.log","fnc=".$input['fnc']." recom_id=".$input['id']);
        $ret = CustomCategoryDefine::chgsortCustomCategory($input);
        if($ret['error_code']==1)   $msg = "必要な情報が不足しています。";
        if($ret['error_code']==-1)  $msg = "表示順の変更に失敗しました。";
        if($ret['error_code']==0)   $msg = "表示順を変更しました。";

        return redirect()->route('admin-custom-category-setting', ['input' => $input, 'msg' => $msg]);
    }
    
}
