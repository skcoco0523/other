<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Advertisement;

class ApiAdvController extends Controller
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
    
    //広告取得
    public function adv_get(Request $request)
    {
        $request = $request->all();
        
        $disp_cnt                       = get_proc_data($request,"disp_cnt");
        $keyword['search_type']         = get_proc_data($request,"search_type");

        $advertisement = Advertisement::getUserAdvlist($disp_cnt, $keyword);     //表示数、ｷｰﾜｰﾄﾞ

        // JSON形式でプレイリストを返す
        return response()->json($advertisement);
    }    
    //広告クリック数反映
    public function adv_click(Request $request)
    {
        $request = $request->all(); 
        $adv_id = get_proc_data($request,"adv_id");
        if($adv_id){
            Advertisement::advCountUp($adv_id);     //対象広告のクリック数追加
        }
    }
    


}
