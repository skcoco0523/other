<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\IotDevice;
use App\Models\IotDeviceSignal;
use App\Models\VirtualRemote;
use App\Models\VirtualRemoteUser;
use App\Models\Mosquitto;



class SmartRemoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //ユーザーのみ
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //IoTデバイス一覧ページ
    public function iotdevice_show(Request $request)
    {
        
        $input['admin_flag']    = false;
        $input['page']          = get_proc_data($input,"page");

        //$input['name_asc']      = true;
        $iotdevice_list = IotDevice::getIotDeviceList(5,true,$input['page'],$input);  //5件
        
        //dd($iotdevice_list);

    
        $virtual_remote_list = VirtualRemote::getVirtualRemoteList(null,false,null,$input);  //全件
        $msg = null;
        //if($iotdevice){
            return view('iotdevice_show', compact('iotdevice_list','virtual_remote_list', 'msg'));
        //}else{
            //return redirect()->route('home')->with('error', '該当の曲が存在しません');
        //}
    }

    //IoTデバイス詳細ページ
    public function iotdevice_show_detail(Request $request)
    {

        $input['admin_flag']    = false;
        $input['page']          = get_proc_data($input,"page");

        $iotdevice = IotDevice::getIotDeviceList(1,false,false,$input);
        
        //対象デバイス所有者チェック
        if ($iotdevice !== null && $iotdevice->isNotEmpty()) {
            $iotdevice = $iotdevice->first();

            //$iotdevice->signal_list 取得デバイスで使用できる処理


            //dd($iotdevice);

            //テスト発信
            //$device_name = "esp32";
            //$type = "ir_signal";
            //$let = Mosquitto::getMqttMessage($device_name,$iotdevice->mac_addr,$type);
            //最新の信号取得
            $let = Mosquitto::getMqttMessage("+",$iotdevice->mac_addr,"+");
            $iotdevice->type = $let['type'];
            $iotdevice->new_mess = $let['mess'];
            dd($iotdevice);
            //dd($mess);
            
            //受信テスト
            //Mosquitto::sendMqttMessage($iotdevice->mac_addr, $let['type'], $let['mess']);

            return view('iotdevice_show_detail', compact('iotdevice', 'msg'));

        }else{
            //デバイス未登録のIDのため強制リダイレクト
            return redirect()->route('home');
        }



    }
    
    //IoTデバイス登録
    public function iotdevice_reg(Request $request)
    {
        make_error_log("iotdevice_reg.log","-----start-----");
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $input['mac_addr']          = get_proc_data($input,"iotdevice_id");
        $input['name']              = get_proc_data($input,"iotdevice_name");

        $user_id = Auth::id();

        make_error_log("iotdevice_reg.log","user_id:".$user_id);
        make_error_log("iotdevice_reg.log","mac_addr:".$input['mac_addr']. "    name:".$input['name']);


        $type = "error";
        if(Auth::user()->dev_reg_lock == 1){
            $msg = "連続で登録に失敗したためロックがかかっています。\n要望・問い合わせにて解除申請してください。"; 
            make_error_log("iotdevice_reg.log","dev_reg_lock");

        }else{
            if($input['mac_addr']){
                //$input = $request->all();
                $iotdevice = IotDevice::getIotDeviceList(1,false,null,$input);  //ユーザーが登録するデバイス確認

                if ($iotdevice !== null && $iotdevice->isNotEmpty()) {

                    //デイバス登録処理
                    $data = array("mac_addr" => $input['mac_addr'] ,"name" => $input['name'] ,"admin_user_id" => $user_id);
                    $let = IotDevice::chgIotDevice($data);

                    if($let['error_code'] == 0){
                        $type = "dev_add";
                        $msg = "デバイスを登録しました。";
                    }elseif($let['error_code'] == 1){
                        $msg = "このデバイスは登録済みです。";
                    }else{
                        $msg = "デバイスの登録に失敗しました。";
                    }
                    

                    session()->forget(['iotdevice_error_count']);   //エラー回数リセット
                }else{
                    // セッションにエラーカウントを保存
                    $errorCount = session()->get('iotdevice_error_count', 0) + 1;
                    session()->put('iotdevice_error_count', $errorCount);
                    if ($errorCount >= 10) {
                        User::chgProfile(["id" => $user_id ,"dev_reg_lock" => 1]);
                        $msg = "デバイスが見つかりませんでした。\n10回連続で失敗したため、ロックがかかりました。";
                    }else {
                        $msg = "該当のデバイスが存在しません。\nあと" . (10 - $errorCount) . "回でロックされます。";      
                    }
                }
            }
        }
        $message = ['message' => $msg, 'type' => $type, 'sec' => '2000'];
        make_error_log("iotdevice_reg.log","msg:".$msg);

        return redirect()->route('iotdevice-show', ['test' => 'test'])->with($message);
    }
    
    //スマートリモコン登録
    public function remote_reg(Request $request)
    {
        make_error_log("remote_reg.log","-----start-----");
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $input['remote_kind']           = get_proc_data($input,"remote_kind");
        $input['blade_id']              = get_proc_data($input,"blade_id");
        $input['remote_name']           = get_proc_data($input,"remote_name");

        $user_id = Auth::id();
        $input['admin_user_id'] = $user_id;

        make_error_log("remote_reg.log","user_id:".$user_id);
        make_error_log("remote_reg.log","remote_kind:".$input['remote_kind']. "    blade_id:".$input['blade_id']. "    remote_name:".$input['remote_name']);

        $ret = VirtualRemote::createVirtualRemote($input);

        if($ret['error_code'] == 0){
            make_error_log("remote_reg.log","createVirtualRemote:success");

            //ユーザー個別リモコン作成　登録者はデフォルトで編集権限あり
            $ret2 = VirtualRemoteUser::createVirtualRemoteUser(['remote_id' => $ret['id'], 'user_id' => $user_id, 'admin_flag' => true,]);

            //test 強制エラー
            //$ret2['error_code'] = 1;
            if($ret2['error_code'] == 0){
                make_error_log("remote_reg.log","createVirtualRemoteUser:success");

            }else{
                make_error_log("remote_reg.log","createVirtualRemoteUser:failure");

                //ユーザー別リモコンの作成に失敗したため、仮想リモコン削除
                $ret3 = VirtualRemote::delVirtualRemote(['id' => $ret['id']]);
                if($ret3['error_code'] == 0){
                    make_error_log("remote_reg.log","delVirtualRemoteUser:success");
                }else{
                    make_error_log("remote_reg.log","delVirtualRemoteUser:failure inconsistency");
                }
            }

        }else{
            make_error_log("remote_reg.log","createVirtualRemote:failure");
        }
        

        $msg = null;
        if($ret['error_code']==0 && $ret2['error_code']==0){
            $msg = "リモコンを追加しました。";
            $type = "remote_add";
        }else{
            $msg = "リモコンの追加に失敗しました。";
            $type = "error";
        }                        

        $message = ['message' => $msg, 'type' => $type, 'sec' => '2000'];
        make_error_log("remote_reg.log","msg:".$msg);

        return redirect()->route('iotdevice-show', ['test' => 'test'])->with($message);

    }
}

