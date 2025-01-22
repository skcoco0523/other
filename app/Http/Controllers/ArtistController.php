<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Artist;
use App\Models\Album;
use App\Models\Music;
use App\Models\Playlist;

class ArtistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //アーティスト情報はゲストも表示可能に
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //アーティスト詳細
    public function artist_show(Request $request)
    {
        //配列になってしまうからキャストする
        $input = $request->all();
        $input['page']                  = get_proc_data($input,"page");

        $art_id = (int) $request->input('id');
        $artist     = Artist::getartist_detail($art_id);
        if($artist){
            $album      = Album::getAlbum_list(10,true,$input['page'],null,$artist->id);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,art_id
            $music      = Music::getMusic_list(10,true,$input['page'],null,$artist->id);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,art_id
            //とりあえずﾌﾟﾚｲﾘｽﾄ情報は不要
            //$keyword['keyword'] = $artist->name;
            //$playlist   = Playlist::getPlaylist_list(10,false,null,$keyword,1);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ,ﾕｰｻﾞｰ
            
        }

        $msg = null;
        if($artist){
            return view('artist_show', compact('artist', 'album', 'music', 'msg'));
        }else{
            return redirect()->route('home')->with('error', '該当のアーティストが存在しません');
        }
    }
}
