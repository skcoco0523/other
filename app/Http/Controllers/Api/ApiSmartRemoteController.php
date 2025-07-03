<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\VirtualRemoteBlade;

class ApiSmartRemoteController extends Controller
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
    
    //使用可能リモコンデザイン取得
    public function remote_blade_get(Request $request)
    {
        make_error_log("remote_blade_get.log","start==================");
        $request = $request->all();
        
        $input['search_kind']            = true;
        $virtualremoteblade_list = VirtualRemoteBlade::getVirtualRemoteBladeList(100,false,null,$input);  //5件

        make_error_log("remote_blade_get.log","virtualremoteblade_list=".print_r($virtualremoteblade_list,1));

        // JSON形式でプレイリストを返す
        return response()->json($virtualremoteblade_list);
    }
    


}
