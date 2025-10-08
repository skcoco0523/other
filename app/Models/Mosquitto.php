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

    //composer require php-mqtt/client　送信にはこれが必要
    //外部deviceにMQTTでメッセージ送信
    public static function publishMQTT($mac_addr, $command, $data)
    {
        $error_log = __FUNCTION__.".log";

        /*$type
        ir_signal:赤外線信号送信命令
        
        */
        $host = localhost;          // MQTTブローカーのホスト名またはIP
        $port = MQTT_BROKER_PORT;   // MQTTポート番号（普通は1883）
    
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

