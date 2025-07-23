<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class IotDeviceSignal extends Model
{
    use HasFactory;
    protected $fillable = ['device_id', 'category_name', 'signal_name', 'signal_data'];     //一括代入の許可
    //protected $table = 'iot_device_signals';

    //IoTデバイス信号一覧取得
    public static function getIotDeviceSignalList($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        make_error_log("getIotDeviceSignalList.log","-------start-------");
        try {
            $sql_cmd = DB::table('iot_device_signals as dev_signal');
            if($keyword){
    
                if (isset($keyword['device_id'])) 
                    $sql_cmd = $sql_cmd->where('dev_signal.device_id',$keyword['device_id']);

                if (isset($keyword['search_cname'])) 
                    $sql_cmd = $sql_cmd->where('dev_signal.category_name', 'like', '%'. $keyword['category_name']. '%');

                if (isset($keyword['search_sname'])) 
                    $sql_cmd = $sql_cmd->where('dev_signal.signal_name', 'like', '%'. $keyword['signal_name']. '%');

                if (isset($keyword['search_remote_id'])) 
                    $sql_cmd = $sql_cmd->where('dev_signal.remote_id', $keyword['search_remote_id']);

                //並び順
                if(get_proc_data($keyword,"cname_asc"))     $sql_cmd = $sql_cmd->orderBy('dev_signal.category_name',    'asc');
                if(get_proc_data($keyword,"sname_asc"))     $sql_cmd = $sql_cmd->orderBy('dev_signal.signal_name',      'asc');
                
                if(get_proc_data($keyword,"cname_desc"))    $sql_cmd = $sql_cmd->orderBy('dev_signal.category_name',    'desc');
                if(get_proc_data($keyword,"sname_desc"))    $sql_cmd = $sql_cmd->orderBy('dev_signal.signal_name',      'desc');
            }
    
            //$sql_cmd                = $sql_cmd->orderBy('created_at', 'desc');
    
            // ページング・取得件数指定・全件で分岐
            if ($pageing){
                if ($disp_cnt === null) $disp_cnt=5;
                $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
            }                       
            elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
            else                                $sql_cmd = $sql_cmd->get();
    
            $IotDeviceSignal_list = $sql_cmd;
            //dd($IotDeviceSignal_list);
            return $IotDeviceSignal_list; 
            
        } catch (\Exception $e) {
            make_error_log("getIotDeviceSignalList.log","failure");
            //ループ処理でエラーになるため、空の配列を返す
            return [];
        }
    }

    //IoTデバイス信号登録
    public static function createIotDeviceSignal($data)
    {
        try {
            make_error_log("createIotDeviceSignal.log","-------start-------");
            
            $device = IotDeviceSignal::where('device_id', $data['device_id'])->where('user_id', $data['user_id'])->first();
            if($device){
                make_error_log("chgIotDeviceSignal.log","error_code:1");
                return ['id' => null, 'error_code' => 1];   //自身で使用済み
            }else{
                $error_code = 0;
                if(!isset($data['device_id']))      $error_code = 1;   //データ不足
                if(!isset($data['user_id']))        $error_code = 2;   //データ不足
                if(!isset($data['device_name']))    $error_code = 3;   //データ不足
                
                if($error_code){
                    make_error_log("createIotDeviceSignal.log","error_code=".$error_code);
                    return ['id' => null, 'error_code' => $error_code];
                }
                
                $request = self::create($data);
                $request_id = $request->id;
                make_error_log("createIotDeviceSignal.log","success");
                make_error_log("createIotDeviceSignal.log","device_id:".$data['device_id']."   user_id:".$data['user_id']);

                return ['id' => $request_id, 'error_code' => $error_code];   //追加成功
            }

        } catch (\Exception $e) {
            make_error_log("createIotDeviceSignal.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
        
    }
    //IoTデバイス信号変更
    public static function chgIotDeviceSignal($data) 
    {
        try {
            make_error_log("chgIotDeviceSignal.log","-------start-------");

            //登録者チェック
            $user_id = Auth::id();
            make_error_log("chgIotDeviceSignal.log","user_id:".$user_id);
            
            $device = IotDeviceSignal::where('mac_addr', $data['mac_addr'])->first();
            if($device){
                if($user_id == $device->admin_user_id){
                    make_error_log("chgIotDeviceSignal.log","error_code:1");
                    return ['id' => null, 'error_code' => 1];   //自身で使用済み
                }elseif($device->admin_user_id != NULL){
                    make_error_log("chgIotDeviceSignal.log","error_code:2");
                    return ['id' => null, 'error_code' => 2];   //他信号にて使用済み
                }
                
                // 更新対象となるカラムと値を連想配列に追加
                $updateData = [];
                if (isset($data['mac_addr']) && $device->mac_addr != $data['mac_addr'])
                    $updateData['mac_addr'] = $data['mac_addr']; 
                
                if (isset($data['ver']) && $device->ver != $data['ver'])
                    $updateData['ver'] = $data['ver']; 
                
                if (isset($data['type']) && $device->type != $data['type'])
                    $updateData['type'] = $data['type']; 
                
                if (isset($data['admin_user_id']) && $device->admin_user_id != $data['admin_user_id'])
                    $updateData['admin_user_id'] = $data['admin_user_id']; 

                make_error_log("chgIotDeviceSignal.log","chg_data=".print_r($updateData,1));
                if(count($updateData) > 0){
                    IotDeviceSignal::where('mac_addr', $data['mac_addr'])->update($updateData);
                    make_error_log("chgIotDeviceSignal.log","success");
                }
                
                return ['mac_addr' => $device->id, 'error_code' => 0];   //更新成功

            } else {
                return ['id' => null, 'error_code' => -1];   //更新失敗
            }

        } catch (\Exception $e) {
            make_error_log("chgIotDeviceSignal.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }

}

