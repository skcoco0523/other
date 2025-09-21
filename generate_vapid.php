<?php
require __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\VAPID;

// 1回だけ実行してキーを作成
$keys = VAPID::createVapidKeys();

// 結果を表示
echo "VAPID_PUBLIC_KEY=" . $keys['publicKey'] . PHP_EOL;
echo "VAPID_PRIVATE_KEY=" . $keys['privateKey'] . PHP_EOL;

// 必要に応じて .env に手動で追記
echo PHP_EOL . "上記キーを .env の以下にセットしてください:" . PHP_EOL;
