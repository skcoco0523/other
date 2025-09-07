<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\MqttClient;  //MQTT
use PhpMqtt\Client\ConnectionSettings;  //MQTT

use Illuminate\Support\Facades\Auth;

class Mosquitto extends Model
{
    use HasFactory;
    //iot_devices

    //protected $table = 'mqtt';
    //protected $fillable = ['user_id', 'type', 'message'];     //一括代入の許可

    //ユーザーがESP32などから送信した情報を取得
    public static function getMqttMessage($device_name,$mac_addr,$type) 
    {
        $error_log = __FUNCTION__." .log";
        // コマンドを定義
        $command = 'timeout 3s mosquitto_sub -h localhost -p 1883 -t "'.$device_name.'/'.$mac_addr.'/'.$type.'" -C 1 -v';
        //$command = 'timeout 1s mosquitto_sub -h localhost -p 1883 -t "'.$device_name.'/+/'.$type.'" -C 1 -v';
        //$command = 'timeout 5s mosquitto_sub -h localhost -p 1883 -t "esp32/+/ir_signal" -C 1';
        //dd($command);
        // 実行して出力を取得
        $data = shell_exec($command);
        make_error_log($error_log, "command: ".$command);
        make_error_log($error_log, "data: ".$data);
        //"esp32/b0fb2f004f8c/ir_signal %KHz%, 3450, 1700, 450, 1250, 450, 400, 0x8121010016cf5aaa, 64, 0, %RepeatPeriod%, %RepeatCnt%"

        if($data){
            $data_array = explode('/', $data);
            $ary = $data_array[2]; //type mess
            $ary2 = explode(' ', $ary);

            $type = $ary2[0]; //type mess
            $mess = trim(str_replace($type, '', $ary));
        }else{
            $type = NULL;
            $mess = NULL;
        }
        //dd($mess);

        return ['type' => $type, 'mess' => $mess];

        //自宅LED点灯
        //"esp32/b0fb2f004f8c/ir_signal %KHz%, 3450, 1700, 450, 1250, 450, 400, 0x8121010016cf5aaa, 64, 0, %RepeatPeriod%, %RepeatCnt%"
    }

    //composer require php-mqtt/client　送信にはこれが必要
    //外部deviceにMQTTでメッセージ送信
    public static function publishMQTT($mac_addr, $command, $data)
    {
        $error_log = __FUNCTION__." .log";

        /*$type
        ir_signal:赤外線信号送信命令
        
        */
        $host = 'localhost';    // MQTTブローカーのホスト名またはIP
        $port = 1883;           // MQTTポート番号（普通は1883）
    
        $topic = "mac_addr" . '/' . $mac_addr;
        $jdata = ['command' => (string)$command, 'data' => (string)$data,];
        $json_message = json_encode($jdata);

        make_error_log($error_log,"topic:".$topic);
        make_error_log($error_log,"jdata:".print_r($jdata,1));
    
        try {
            $clientId = uniqid(); // 適当なクライアントID（被らなければOK）
            $mqtt = new MqttClient($host, $port, $clientId);
    
            // ConnectionSettings オブジェクトを作成
            $settings = (new ConnectionSettings())
                ->setConnectTimeout(3);  // 接続タイムアウトを3秒に設定
    
            // 接続設定を渡して接続
            $mqtt->connect($settings);  // 引数には ConnectionSettings オブジェクト
    
            // 接続が成功したか確認
            if (!$mqtt->isConnected()) {
                throw new \Exception("MQTT接続に失敗しました。");
            }
            
            $QoS_level = 0; //0:1回だけ送信(未接続時送信無し)
            $retain_flag = false;   //true：受信側未接続状態で保持させる　false:保持しない
            // メッセージ送信
            $mqtt->publish($topic, $json_message, $QoS_level, $retain_flag);
    
            // サーバー切断
            $mqtt->disconnect();
        } catch (\PhpMqtt\Client\Exceptions\MqttClientException $e) {
            make_error_log($error_log, "failure: ".$e->getMessage());
            return false;
        } catch (\Exception $e) {
            make_error_log($error_log, "failure: ".$e->getMessage());
            return false;
        }
    
        make_error_log($error_log, "success");
        return true;
    }

}

