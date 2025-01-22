<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Music;
use App\Models\CustomCategory;


class MusicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //ホームはゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //曲詳細
    public function music_show(Request $request)
    {
        //$music_id = $request->only(['id']);   配列になってしまうからキャストする
        $music_id = (int) $request->input('id');
        if($music_id){
            $music = Music::getMusic_detail($music_id);
            if(Auth::id()){
                $custom_category = CustomCategory::chkCustomCategory(Auth::id(),$music_id);//ユーザーID、music_id指定、カテゴリ指定(ビット番号)
            }else{
                $custom_category = null;
            }
            $msg = null;
            //dd($music);
        }

        if($music){
            return view('music_show', compact('music', 'custom_category', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当の曲が存在しません');
        }
    }
    
}
