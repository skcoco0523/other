<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use App\Models\IotDeviceSignal;

class IotDevice extends Model
{
    use HasFactory;
    protected $fillable = ['mac_addr', 'name', 'type', 'ver', 'pincode'];     //一括代入の許可

    //IoTデバイス一覧取得
    public static function getIotDeviceList($disp_cnt=null,$pageing=false,$page=1,$keyword=null)
    {
        make_error_log("getIotDeviceList.log","-------start-------");
        try {
            $sql_cmd = DB::table('iot_devices as dev');
            $sql_cmd = $sql_cmd->leftJoin('users', 'dev.admin_user_id', '=', 'users.id');
            $sql_cmd = $sql_cmd->select('dev.*', 'users.id as uid', 'users.name as uname', 'dev.name as name');
            if($keyword){
                //管理者による検索
                if(get_proc_data($keyword,"admin_flag")){

                    if (isset($keyword['search_addr'])) 
                        $sql_cmd = $sql_cmd->where('dev.mac_addr', 'like', '%'. $keyword['search_addr']. '%');

                    if (isset($keyword['mac_addr'])) 
                        $sql_cmd = $sql_cmd->where('dev.mac_addr', $keyword['mac_addr']);

                    if (isset($keyword['search_owner_id'])) 
                        $sql_cmd = $sql_cmd->where('dev.admin_user_id',$keyword['search_owner_id']);

                    if (isset($keyword['search_type'])) 
                        $sql_cmd = $sql_cmd->where('dev.type',$keyword['search_type']);

                //ユーザーによる検索
                }else{      
                    //登録時の対象検索
                    if (isset($keyword['iotdevice_id'])){
                        $sql_cmd = $sql_cmd->where('dev.mac_addr',$keyword['iotdevice_id']);

                    }else{
                        //登録時点の検索以外では所持しているデバイスのみ
                        $sql_cmd = $sql_cmd->where('dev.admin_user_id', Auth::id());
                    }
                    //$sql_cmd->orderBy('dev.name','asc');
                }
                //並び順
                //if(get_proc_data($keyword,"name_asc"))      $sql_cmd = $sql_cmd->orderBy('dev.device_name',     'asc');
                if(get_proc_data($keyword,"cdate_asc"))     $sql_cmd = $sql_cmd->orderBy('dev.created_at',      'asc');
                if(get_proc_data($keyword,"udate_asc"))     $sql_cmd = $sql_cmd->orderBy('dev.updated_at',      'asc');
                
                if(get_proc_data($keyword,"cdate_desc"))    $sql_cmd = $sql_cmd->orderBy('dev.created_at',      'desc');
                if(get_proc_data($keyword,"udate_desc"))    $sql_cmd = $sql_cmd->orderBy('dev.updated_at',      'desc');
            }
    
            //$sql_cmd                = $sql_cmd->orderBy('created_at', 'desc');
    
            // ページング・取得件数指定・全件で分岐
            if ($pageing){
                if ($disp_cnt === null) $disp_cnt=5;
                $sql_cmd = $sql_cmd->paginate($disp_cnt, ['*'], 'page', $page);
            }                       
            elseif($disp_cnt !== null)          $sql_cmd = $sql_cmd->limit($disp_cnt)->get();
            else                                $sql_cmd = $sql_cmd->get();
    
            $iotdevice_list = $sql_cmd;

            //dd($iotdevice_list);
            foreach($iotdevice_list as $key => $iotdevice){
                //デバイスの信号を取得
                $iotdevice->signal_list = IotDeviceSignal::getIotDeviceSignalList(null,false,false,["device_id"=>$iotdevice->id]);

                //テーブル用アイコン定義
                $iotdevice->icon_class = config('common.device_type_icons')[$iotdevice->type] ?? null;
            }

            //dd($iotdevice_list);

            return $iotdevice_list; 
            
        } catch (\Exception $e) {
            make_error_log("getIotDeviceList.log","failure");
            //ループ処理でエラーになるため、空の配列を返す
            return [];
        }
    }

    //IoTデバイス登録
    public static function createIotDevice($data)
    {
        make_error_log("createIotDevice.log","-------start-------");
        try {

            $error_code = 0;
            if(!isset($data['mac_addr']))       $error_code = 1;   //データ不足
            if(!isset($data['type']))           $error_code = 2;   //データ不足
            if(!isset($data['ver']))            $error_code = 3;   //データ不足
            if(!isset($data['pincode']))        $error_code = 4;   //データ不足
            
            
            $keyword = array('admin_flag'=>true,'mac_addr'=>$data['mac_addr']);
            $iotdevice_list = IotDevice::getIotDeviceList(1,false,null,$keyword);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
            //dd($iotdevice_list);
            if($error_code){
                make_error_log("createIotDevice.log","error_code=".$error_code);
                return ['id' => null, 'error_code' => $error_code];
            }
            //mac_addrのユニーク規制
            if(count($iotdevice_list)!=0) {
                $error_code = -2;
                return ['id' => null, 'error_code' => $error_code];
            }

            //dd($data);
            $request = self::create($data);
            $request_id = $request->id;

            make_error_log("createIotDevice.log","success");
            
            return ['id' => $request_id, 'error_code' => $error_code];   //追加成功

        } catch (\Exception $e) {
            make_error_log("createIotDevice.log", "Error Message: " . $e->getMessage());
            return ['id' => null, 'error_code' => -1];   //追加失敗
        }
        
    }
    //IoTデバイス変更
    public static function chgIotDevice($data) 
    {
        try {
            make_error_log("chgIotDevice.log","-------start-------");

            if(get_proc_data($data,"admin_flag")){    //管理画面での更新
                $device = IotDevice::where('id', $data['id'])->first();
            
            }else{              //ユーザーによるデバイス登録             
                //登録者チェック
                $user_id = Auth::id();
                make_error_log("chgIotDeviceUser.log","user_id:".$user_id);
                $device = IotDevice::where('mac_addr', $data['mac_addr'])->first();

                if($user_id == $device->admin_user_id){
                    make_error_log("chgIotDevice.log","error_code:1");
                    return ['id' => null, 'error_code' => 1];   //自身で使用済み
                }elseif($device->admin_user_id != NULL){
                    make_error_log("chgIotDevice.log","error_code:2");
                    return ['id' => null, 'error_code' => 2];   //他ユーザーにて使用済み
                }
            }

            if(!$device){
                make_error_log("chgIotDevice.log",".not applicable:".$data['mac_addr']);
                return ['id' => null, 'error_code' => -1];   //更新失敗
            }
            //dd($data);
            
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
            
            if (isset($data['name']) && $device->name != $data['name'])
                $updateData['name'] = $data['name']; 


            make_error_log("chgIotDevice.log","chg_data=".print_r($updateData,1));
            if(count($updateData) > 0){
                IotDevice::where('id', $device->id)->update($updateData);
                make_error_log("chgIotDevice.log","success");
            }
            
            return ['id' => $device->id, 'error_code' => 0];   //更新成功

        } catch (\Exception $e) {
            make_error_log("chgIotDevice.log","failure");
            return ['error_code' => -1];   //更新失敗
        }
    }
    //IoTデバイス削除
    public static function delIotDevice($data)
    {
        try {
            make_error_log("delIotDevice.log","delete_id=".$data['id']);
            $user_id = Auth::id();

            if(get_proc_data($data,"admin_flag")){    //管理画面での削除
                //他データはリレーションでカスケード削除
                IotDevice::where('id', $data['id'])->delete();
                make_error_log("delIotDevice.log","admin_id=".$user_id);
            
            }else{                      //ユーザーによるデバイス削除  
                //IotDevice::where('id', $data['id'])->where('admin_user_id', Auth::id())->delete();
                //ユーザーからは削除せず、所有者の解除のみ
            }


            make_error_log("delIotDevice.log","success");
            return ['id' => null, 'error_code' => 0];   //削除成功

        } catch (\Exception $e) {
            make_error_log("delIotDevice.log","failure");
            return ['id' => null, 'error_code' => -1];   //削除失敗

        }
    }
}

