<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Album;

class AlbumController extends Controller
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
    //アルバム詳細
    public function album_show(Request $request)
    {
        //配列になってしまうからキャストする
        $alb_id = (int) $request->input('id');
        $album = Album::getAlbum_detail($alb_id);  //mus_id
        $msg = null;
        if($album){
            return view('album_show', compact('album', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のアルバムが存在しません');
        }
    }

    
}
