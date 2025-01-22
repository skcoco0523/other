<?php

return [
    //reCAPTCHA 不正登録対応
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'secret' => env('NOCAPTCHA_SECRET'),
    'options' => [
        'timeout' => 30,
    ],
];
