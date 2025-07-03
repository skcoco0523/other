<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class IotDeviceUser extends Model
{
    use HasFactory;
    protected $fillable = ['device_id', 'user_id', 'device_name'];     //一括代入の許可
    //protected $table = 'iot_device_users';

    //IoTデバイスユーザー一覧取得
    public static function getIotDeviceUserList($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        make_error_log("getIotDeviceUserList.log","-------start-------");
        try {
            $sql_cmd = DB::table('iot_device_users as dev_user');
            $sql_cmd = $sql_cmd->leftJoin('iot_devices as dev', 'dev_user.id', '=', 'dev.id');
            $sql_cmd = $sql_cmd->leftJoin('users', 'dev.admin_user_id', '=', 'users.id');
            if($keyword){
    
                //管理者による検索
                if(get_proc_data($keyword,"admin_flag")){
                    $sql_cmd = $sql_cmd->leftJoin('user', 'user.id', '=', 'dev_user.user_id');

                    if (isset($keyword['search_addr'])) 
                        $sql_cmd = $sql_cmd->where('dev.mac_addr', 'like', '%'. $keyword['search_addr']. '%');

                    if (isset($keyword['search_owner_id'])) 
                        $sql_cmd = $sql_cmd->where('dev.admin_user_id',$keyword['search_owner']);

                    if (isset($keyword['search_type'])) 
                        $sql_cmd = $sql_cmd->where('dev.type',$keyword['search_type']);

                //ユーザーによる検索
                }else{      
                    //自身のdeviceのみ
                    $user_id = Auth::id();
                    $sql_cmd = $sql_cmd->where('dev_user.user_id', $user_id);
                    
                    //所持デバイス(管理者)
                    if (isset($keyword['my_device_flag'])) 
                        $sql_cmd = $sql_cmd->where('dev.admin_user_id', $user_id);
                    
                    //$sql_cmd->orderBy('dev.name','asc');
                }
                //並び順
                if(get_proc_data($keyword,"name_asc"))      $sql_cmd = $sql_cmd->orderBy('dev_user.device_name',    'asc');
                if(get_proc_data($keyword,"cdate_asc"))     $sql_cmd = $sql_cmd->orderBy('dev.created_at',          'asc');
                if(get_proc_data($keyword,"udate_asc"))     $sql_cmd = $sql_cmd->orderBy('dev.updated_at',          'asc');
                
                if(get_proc_data($keyword,"cdate_desc"))    $sql_cmd = $sql_cmd->orderBy('dev.created_at',          'desc');
                if(get_proc_data($keyword,"udate_desc"))    $sql_cmd = $sql_cmd->orderBy('dev.updated_at',          'desc');
            }
    
            //$sql_cmd                = $sql_cmd->orderBy('created_at', 'desc');
    
            // ページング・取得件数指定・全件で分岐
            if ($pageing){
                if ($disp_cnt === null) $disp_cnt=5;
                $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
            }                       
            elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
            else                                $sql_cmd = $sql_cmd->get();
    
            $IotDeviceUser_list = $sql_cmd;
            //dd($IotDeviceUser_list);
            foreach($IotDeviceUser_list as $key => $iotdevice){
                //デバイスの信号を取得
                $iotdevice->signal_list = IotDeviceSignal::getIotDeviceSignalList(null,false,false,["device_id"=>$iotdevice->device_id]);
            }

            return $IotDeviceUser_list; 
            
        } catch (\Exception $e) {
            make_error_log("getIotDeviceUserList.log","failure");
            //ループ処理でエラーになるため、空の配列を返す
            return [];
        }
    }

    //IoTデバイスユーザー登録
    public static function createIotDeviceUser($data)
    {
        try {
            make_error_log("createIotDeviceUser.log","-------start-------");
            
            $device = IotDeviceUser::where('device_id', $data['device_id'])->where('user_id', $data['user_id'])->first();
            if($device){
                make_error_log("chgIotDeviceUser.log","error_code:1");
                return ['id' => null, 'error_code' => 1];   //自身で使用済み
            }else{
                $error_code = 0;
                if(!isset($data['device_id']))      $error_code = 1;   //データ不足
                if(!isset($data['user_id']))        $error_code = 2;   //データ不足
                if(!isset($data['device_name']))    $error_code = 3;   //データ不足
                
                if($error_code){
                    make_error_log("createIotDeviceUser.log","error_code=".$error_code);
                    return ['id' => null, 'error_code' => $error_code];
                }
                
                $request = self::create($data);
                $request_id = $request->id;
                make_error_log("createIotDeviceUser.log","success");
                make_error_log("createIotDeviceUser.log","device_id:".$data['device_id']."   user_id:".$data['user_id']);

                return ['id' => $request_id, 'error_code' => $error_code];   //追加成功
            }

        } catch (\Exception $e) {
            make_error_log("createIotDeviceUser.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
        
    }
    //IoTデバイスユーザー変更
    public static function chgIotDeviceUser($data) 
    {
        try {
            make_error_log("chgIotDeviceUser.log","-------start-------");

            //登録者チェック
            $user_id = Auth::id();
            make_error_log("chgIotDeviceUser.log","user_id:".$user_id);
            
            $device = IotDeviceUser::where('mac_addr', $data['mac_addr'])->first();
            if($device){
                if($user_id == $device->admin_user_id){
                    make_error_log("chgIotDeviceUser.log","error_code:1");
                    return ['id' => null, 'error_code' => 1];   //自身で使用済み
                }elseif($device->admin_user_id != NULL){
                    make_error_log("chgIotDeviceUser.log","error_code:2");
                    return ['id' => null, 'error_code' => 2];   //他ユーザーにて使用済み
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

                make_error_log("chgIotDeviceUser.log","chg_data=".print_r($updateData,1));
                if(count($updateData) > 0){
                    IotDeviceUser::where('mac_addr', $data['mac_addr'])->update($updateData);
                    make_error_log("chgIotDeviceUser.log","success");
                }
                
                return ['mac_addr' => $device->id, 'error_code' => 0];   //更新成功

            } else {
                return ['id' => null, 'error_code' => -1];   //更新失敗
            }

        } catch (\Exception $e) {
            make_error_log("chgIotDeviceUser.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }

}

