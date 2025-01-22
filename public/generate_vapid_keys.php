<?php
require 'vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$keys = VAPID::createVapidKeys();
echo "VAPID Public Key: {$keys['publicKey']}\n";
echo "VAPID Private Key: {$keys['privateKey']}\n";