<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        make_error_log("remote_blade_get.log","=========start=========");
        $input = $request->all();
        
        $input['search_kind']           = get_proc_data($input,"search_kind");
        $virtualremoteblade_list = VirtualRemoteBlade::getVirtualRemoteBladeList(null,false,null,$input);
        $blade_list = [];
        foreach($virtualremoteblade_list as $key => $blade){
            $views_path = config('common.smart_remote_blade_paht') ."." . substr($blade->blade_name, 0, -6); 

            if (View::exists($views_path)) {
                try {
                    $htmlContent = View::make($views_path)->render();
                } catch (\Exception $e) {
                    make_error_log("remote_blade_get.log","error_mess". $e->getMessage());
                    $htmlContent = '<p style="color: red;">プレビューのレンダリングに失敗しました。</p>';
                }
            } else {
                make_error_log("remote_blade_get.log","views_path:ng");
                $htmlContent = '<p style="color: orange;">デザインファイルが見つかりません。</p>';
            }

            $blade_list[] = [
                'id'           => $blade->id,
                'html_content' => $htmlContent,           // レンダリング済みHTMLコンテンツ
            ];
        }

        // JSON形式でプレイリストを返す
        return response()->json($blade_list);
    }
    


}
