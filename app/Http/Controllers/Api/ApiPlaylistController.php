<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\Playlist;

class ApiPlaylistController extends Controller
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
    
    //マイプレイリスト取得　モーダルで使用する
    public function myplaylist_get()
    {
        $playlists = Playlist::getPlaylist_list(999, false, null, ['user_id' => true]);

        // JSON形式でプレイリストを返す
        return response()->json($playlists);
    }
    
}
