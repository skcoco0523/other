<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomCategory;

class CategoryController extends Controller
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

    //カテゴリー状態変更
    public function custom_category_chg(Request $request)
    {
        $music_id = $request->input('music_id');
        $bit_num = $request->input('bit_num');
        $type = $request->input('type');    //add or del

        //make_error_log("custom_category_chg.log","music_id=".$music_id."  bit_num=".$bit_num."  type=".$type);
        $ret = CustomCategory::chgCustomCategory(Auth::id(), $music_id, $bit_num, $type);

        return $ret;
    }
}
