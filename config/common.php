<?php

//設定ファイルを追加・変更したら以下のコマンドで設定キャッシュを再生成
//php artisan config:cache

return [

    'admin_memo_path' => storage_path('app/admin_memo'), // 管理者メモの保存ディレクトリ
    'smart_remote_blade_paht' => 'smart_remote', // スマートリモコンデザインの保存ディレクトリ

    //=====================================================
    //フレンドリスト
    //=====================================================
    'friend_status' => [
        'pending'  => 0, // 未承認
        'accepted' => 1, // 承認済み
        'declined' => 2, // 拒否済み
    ],

    //=====================================================
    // ユーザーリクエスト
    //=====================================================
    'request_type' => [
        'request'  => 0, // 要望
        'inquiry' => 1, // 問い合わせ
    ],
    
    'request_status' => [
        'unresolved' => 0, // 未対応
        'resolved'   => 1, // 対応済
    ],

    //=====================================================
    // IoTデバイス
    //=====================================================
    'device_type' => [
        'IRRemote'  => 0, // 赤外線リモコン
        'SmartLock' => 1, // スマートロック
    ],
    
    // 値は、config('common.device_type') の値（0, 1, 2...）と一致させる
    //アイコン情報：https://fontawesome.com/
    'device_type_icons' => [
        0 => 'fa-tower-broadcast',
        1 => 'fa-lightbulb', 
    ],
    //=====================================================
    // 仮想リモコン
    //=====================================================
    'remote_kind' => [
        'テレビ'       => 0,
        '照明'       => 1,
        'エアコン'     => 2,
        'ロボット掃除機' => 3,
        'オーディオ'   => 4,
        'プロジェクター' => 5,
        '扇風機'     => 6,
        'ブルーレイ・DVD' => 7,
        'その他' => 99,
    ],

    // 値は、config('common.remote_kind') の値（0, 1, 2...）と一致させる
    //アイコン情報：https://fontawesome.com/
    'remote_kind_icons' => [
        0 => 'fa-tv',
        1 => 'fa-lightbulb', 
        2 => 'fa-wind',
        3 => 'fa-robot',
        4 => 'fa-volume-high',
        5 => 'fa-video',
        6 => 'fa-fan',
        7 => 'fa-compact-disc',
        99 => 'fa-question',
    ],
];