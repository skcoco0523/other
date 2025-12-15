<?php
//「php artisan mqtt:listen」を実行してMQTTメッセージをリッスンする

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Mosquitto; 
use App\Models\IotDevice; 


class MqttListener extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen for MQTT messages for new device registration.';

    public function handle()
    {

        $error_log = "subscribeMQTT.log";
        make_error_log($error_log,"--------setup start---------");
        $server = env('MQTT_BROKER_HOST', 'localhost');
        // localhost の場合だけ実際の LAN IP を取得して上書き
        if ($server === 'localhost' || $server === '127.0.0.1') {
            $server = gethostbyname(gethostname());
        }
        $port = env('MQTT_BROKER_PORT', 1883);
        $clientId = env('MQTT_CLIENT_ID', 'laravel_mqtt_client');

        make_error_log($error_log,"server:".$server."  port:".$port."  clientId:".$clientId);
        $mqtt = new MqttClient($server, $port, $clientId);
        
        /*
        device-access： ESPデバイス起動後のアクセス通知
        ir-signal：     IRデバイスからの赤外線信号登録要求
        */

        $subscribe_callback = function ($topic, $message) use ($error_log) {
            make_error_log($error_log,"-------subscribe check--------");
            $topic_array = explode('/', $topic);
            $mac_addr = $topic_array[1] ?? null;

            // JSONペイロードを解析
            $data = json_decode($message, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error("Failed to decode JSON from message. Error: " . json_last_error_msg());
                make_error_log($error_log,"Failed to decode JSON from message. Error: " . json_last_error_msg());
                return;
            }

            $mac_addr       = $data['mac_addr'] ?? null;
            $device_name    = $data['device_name'] ?? null;
            $command        = $data['command'] ?? null;
            // デバイスタイプは後で選択する
            //$type       = $data['type'] ?? null;
            //$type_num   = config('common.device_type')[$type] ?? null;
            $ver            = $data['ver'] ?? null;
            $data           = $data['data'] ?? null;

            $this->info('topic:'. $topic);
            //make_error_log($error_log,"mac_addr:".$mac_addr." type:".$type." type_num:".s$type_num." ver:".$ver." uid:".$uid." data:".$data);
            make_error_log($error_log,"mac_addr:".$mac_addr." device_name:".$device_name." command:".$command." ver:".$ver." data:".$data);

            if ($command == 'device-access') {
                // デバイスタイプは後で選択する
                //if(is_numeric($type_num) == false){
                //    make_error_log($error_log,"config/common.php on not found device_type:".$type); return Command::FAILURE;
                //}
                //$device_list = IotDevice::getIotDeviceList(1,false,NULL,['admin_flag' => true, 'search_addr' => $mac_addr]);
                $device = IotDevice::getIotDeviceList(1,false,NULL,['admin_flag' => true, 'search_addr' => $mac_addr])->first();
                //if ($device_list !== null && $device_list->isNotEmpty()) {
                if ($device !== null) {
                    //登録済みデバイス
                    make_error_log($error_log,"device registered...mac_addr:".$device->mac_addr);
                    //本登録されるまでにESPデバイスが再起動した場合に備えてpiccodeを再送
                    if($device->admin_user_id == null){
                            
                        $jdata = json_encode(["pincode" => (String)$device->pincode]);
                        Mosquitto::publishMQTT($mac_addr, "temp_regist", $jdata);
                    }else{
                        Mosquitto::publishMQTT($mac_addr, "final_regist"); //登録済み通知
                    }

                }else{
                    //未登録デバイス
                    make_error_log($error_log,"device not found...creating");
                    
                    //未登録デバイスは、本登録対象を検索するためデバイス名を必須とする
                    if (empty($device_name)) {
                        make_error_log($error_log,"not found device_name:".$device_name); return Command::FAILURE;
                    }
                    $pincode = random_int(100000, 999999); // 6文字のランダムな文字列を生成
                    // ユニークなpincodeになるまで繰り返す
                    while (IotDevice::where('pincode', $pincode)->exists()) { $pincode = random_int(100000, 999999); }
                    make_error_log($error_log,"pincode:".$pincode);
                    //type:99=未設定
                    $ret = IotDevice::createIotDevice(["mac_addr" => $mac_addr, "type" => 99, "name" => $device_name, "ver" => $ver, "pincode" => $pincode]);
                    if($ret['error_code'] == 0){
                        //登録成功　piccodeをESPデバイスに送信
                        make_error_log($error_log,"device create success id:".$ret['id']);
                        $jdata = json_encode(["pincode" => (String)$pincode]);
                        Mosquitto::publishMQTT($mac_addr, "temp_regist", $jdata);
                        
                    }else{
                        //登録失敗
                        make_error_log($error_log,"device create error_code:".$ret['error_code']);
                        return Command::FAILURE;

                    }
                }
                
            } elseif ($command == 'ir-signal') {
                // IR信号登録処理

            }
            
            make_error_log($error_log,"--------end---------");
        };

        // 無限ループで永続的な実行を保証
        while (true) {
            try {
                if (!$mqtt->isConnected()) {
                    
                    // 接続が切断されていたら再接続
                    $this->info('Attempting to connect to MQTT broker...');
                    try {
                        $mqtt->connect(null, false);
                        make_error_log($error_log,"Connected to MQTT broker");
                    } catch (\Exception $e) {
                        make_error_log($error_log,"MQTT connect failed: ".$e->getMessage());
                    }

                    // device_send/XXXXX のトピックを購読
                    $mqtt->subscribe('device_send/#', $subscribe_callback);
                }

                $mqtt->loop(true);

            } catch (\Exception $e) {
                make_error_log($error_log,"error: ".$e->getMessage());
                sleep(5); // 5秒待機してから再試行
            }
        }
        return Command::SUCCESS;
    }
}