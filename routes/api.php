<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\Auth\ApiLoginController;
//use App\Http\Controllers\Api\ApiPlaylistController;
use App\Http\Controllers\Api\ApiAdvController;
use App\Http\Controllers\Api\ApiSmartRemoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// ログインエンドポイントを追加
//Route::post('/login', [ApiLoginController::class, 'login']);

// 認証済みユーザー向けルート (Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    //ユーザー情報取得
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // マイプレイリスト取得
    //Route::get('/myplaylist/get', [ApiPlaylistController::class, 'myplaylist_get']);

    // リモコンデザイン検索
    Route::get('/remote-blade/get', [ApiSmartRemoteController::class, 'remote_blade_get']);

    // 所有iotデバイス検索
    Route::get('/iot_devices/get', [ApiSmartRemoteController::class, 'iot_devices_get']);
    
});

//未認証ユーザー


// 広告情報取得
Route::get('/adv/get', [ApiAdvController::class, 'adv_get']);
Route::post('/adv/click', [ApiAdvController::class, 'adv_click']);