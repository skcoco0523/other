<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class VirtualRemoteUser extends Model
{
    use HasFactory;
    protected $fillable = ['remote_id', 'user_id', 'admin_flag', 'stop_flag'];     //一括代入の許可
    //protected $table = 'virtual_remote_users';

    //ユーザー別仮想リモコン一覧取得



    //ユーザー別仮想リモコン登録
    public static function createVirtualRemoteUser($data)
    {
        make_error_log("createVirtualRemoteUser.log","-------start-------");
        try {

            $error_code = 0;
            if(!isset($data['remote_id']))      $error_code = 1;   //データ不足
            if(!isset($data['user_id']))        $error_code = 2;   //データ不足
            
            if($error_code){
                make_error_log("createVirtualRemoteUser.log","error_code=".$error_code);
                return ['id' => null, 'error_code' => $error_code];
            }
            
            //dd($data);
            $request = self::create($data);
            $request_id = $request->id;
            make_error_log("createVirtualRemoteUser.log","success");
            return ['id' => $request_id, 'error_code' => $error_code];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createVirtualRemoteUser.log","failure");
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
        
        
    }
    
    //ユーザー別仮想リモコン削除
    public static function delVirtualRemoteUser($data)
    {
        try {
            //他データはリレーションでカスケード削除
            make_error_log("delVirtualRemoteUser.log","delete_id=".$data['id']);
            self::where('id', $data['id'])->delete();

            make_error_log("delVirtualRemoteUser.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功

        } catch (\Exception $e) {
            make_error_log("delVirtualRemoteUser.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗

        }
    }

    //ユーザー別仮想リモコン変更
    public static function chgVirtualRemoteUser($data) 
    {
        try {
            make_error_log("chgVirtualRemoteUser.log","-------start-------");

            //登録者チェック
            $user_id = Auth::id();
            make_error_log("chgVirtualRemoteUser.log","user_id:".$user_id);
            
            $device = IotDeviceUser::where('mac_addr', $data['mac_addr'])->first();
            if($device){
                if($user_id == $device->admin_user_id){
                    make_error_log("chgVirtualRemoteUser.log","error_code:1");
                    return ['id' => null, 'error_code' => 1];   //自身で使用済み
                }elseif($device->admin_user_id != NULL){
                    make_error_log("chgVirtualRemoteUser.log","error_code:2");
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

                make_error_log("chgVirtualRemoteUser.log","chg_data=".print_r($updateData,1));
                if(count($updateData) > 0){
                    IotDeviceUser::where('mac_addr', $data['mac_addr'])->update($updateData);
                    make_error_log("chgVirtualRemoteUser.log","success");
                }
                
                return ['mac_addr' => $device->id, 'error_code' => 0];   //更新成功

            } else {
                return ['id' => null, 'error_code' => -1];   //更新失敗
            }

        } catch (\Exception $e) {
            make_error_log("chgVirtualRemoteUser.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }

}

