<?php

//namespace App\Helpers;

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use App\Models\UserDevice;
// app/Helpers/PushNotification.php

// プッシュ通知関数
/*  使用例
    $user_prf = User::profile_get($user_id);
    $send_info = [
        'title' => 'フレンド申請',
        'body' => $user_prf->name.'からフレンド申請が届きました',
        'url' => route('friendlist-show', ['table' => 'request']),
    ];
    push_send($friend_id,$send_info);
*/
if (! function_exists('push_send')) {
    //$send_info(title,body)
    function push_send($user_id, $send_info){
        return PushNotification::sendNotification($user_id, $send_info);
    }
}

function urlSafeBase64Decode($base64Url) {
    $base64 = strtr($base64Url, '-_', '+/');
    return base64_decode($base64);
}
class PushNotification
{
    
    public static function sendNotification($user_id, $send_info)
    {
        $error_log = __FUNCTION__." .log";
        make_error_log($error_log, "========================start========================");
        make_error_log($error_log, "user_id: ".$user_id);
        $user_devices = UserDevice::getUserDevices($user_id);
        if (!$user_devices) {
            make_error_log($error_log, "user_devices is null");
            return;
        }
        $subscription = Subscription::create([
            'endpoint' => $user_devices['endpoint'],
            'publicKey' => $user_devices['public_key'], 
            'authToken' => $user_devices['auth_token'], 
        ]);

        $auth = [
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'), // 管理者のメールアドレスを設定ファイルから取得
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];
    
        make_error_log($error_log, "subscription: " . print_r($subscription,1));
        make_error_log($error_log, "auth: " . print_r($auth,1));


        /*使用例
        $send_info = [
            'title' => 'テストタイトル',
            'body' => 'テストメッセージ',
            'icon' => '/path/to/icon.png',
            'url' => 'https://skcoco.com/other',
            'badge' => '/path/to/badge.png',
            'data' => [
                'some_key' => 'some_value',
                'another_key' => 'another_value'
            ]
        ];
        */
        
        $webPush = new WebPush($auth);
        try {
            $report = $webPush->sendOneNotification(
                $subscription,
                json_encode($send_info)
            );
            $reason = $report->getReason();
            make_error_log($error_log, "Reason: " . $reason);
            
        } catch (\Exception $e) {
            // 例外の詳細をログに出力
            make_error_log($error_log, "Error Message: " . $e->getMessage());
            make_error_log($error_log, "Trace:". $e->getTraceAsString());
        }
    }
}

