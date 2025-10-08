<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

//管理者
use App\Http\Middleware\AdminMiddleware;

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAdvController;
use App\Http\Controllers\Admin\AdminSmartRemoteController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminAnotherController;



//ユーザー
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\UserDeviceController;
use App\Http\Controllers\Auth\LineLoginController;
use App\Http\Controllers\FriendlistController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RouletteController;
use App\Http\Controllers\SmartRemoteController;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

// 未認証ユーザー向け
Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('linelogin', [LineLoginController::class, 'lineLogin'])->name('linelogin');
Route::get('callback', [LineLoginController::class, 'callback'])->name('callback');

//ユーザー------------------------------------------------------------------------
//PWAアプリインストール時の　デバイス情報登録
Route::post('devices/check', [UserDeviceController::class, 'device_update'])->name('devices-check');

//パスワードリセット
Route::post('password/reset/mailsend', [UserController::class, 'password_reset_mailsend'])->name('password-reset');


Route::get('roulette/show', [RouletteController::class, 'roulette_show'])->name('roulette-show');


// 認証済みユーザー向け
Route::middleware(['auth'])->group(function () {
    /*
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    */
    
    // 管理者権限があるユーザーのみ------------------------------------------------------------------------
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::group(['prefix' => 'admin'], function(){

            Route::get('home', [AdminHomeController::class, 'home'])->name('admin-home');

            //----------------------------------------------------------------------------------
            //ユーザー------------------------------------------------------------------------
            //----------------------------------------------------------------------------------
            //一覧
            Route::get('user/search', [AdminUserController::class, 'user_search'])->name('admin-user-search');
            Route::post('user/search/chg', [AdminUserController::class, 'user_chg'])->name('admin-user-chg');
            //依頼・要望
            Route::get('user/repuest', [AdminUserController::class, 'user_request_search'])->name('admin-request-search');
            Route::post('user/repuest/chg', [AdminUserController::class, 'user_request_chg'])->name('admin-request-chg');
            //----------------------------------------------------------------------------------


            //----------------------------------------------------------------------------------
            //IoTデバイス------------------------------------------------------------------------
            //----------------------------------------------------------------------------------
            //デバイス一覧
            Route::get('iotdevice/search', [AdminSmartRemoteController::class, 'iotdevice_search'])->name('admin-iotdevice-search');
            //デバイス登録
            Route::get('iotdevice/reg', [AdminSmartRemoteController::class, 'iotdevice_regist'])->name('admin-iotdevice-reg');
            Route::post('iotdevice/reg', [AdminSmartRemoteController::class, 'iotdevice_reg'])->name('admin-iotdevice-reg');
            //デバイス検索>変更
            Route::post('iotdevice/chg', [AdminSmartRemoteController::class, 'iotdevice_chg'])->name('admin-iotdevice-chg');
            //デバイス検索>削除
            Route::post('iotdevice/del', [AdminSmartRemoteController::class, 'iotdevice_del'])->name('admin-iotdevice-del');
            //----------------------------------------------------------------------------------


            //----------------------------------------------------------------------------------
            //リモコン------------------------------------------------------------------------
            //----------------------------------------------------------------------------------
            //リモコンデザイン一覧
            Route::get('virtualremote-blade/search', [AdminSmartRemoteController::class, 'virtualremote_blade_search'])->name('admin-virtualremote-blade-search');
            //リモコンデザイン登録
            Route::get('virtualremote-blade/reg', [AdminSmartRemoteController::class, 'virtualremote_blade_regist'])->name('admin-virtualremote-blade-reg');
            Route::post('virtualremote-blade/reg', [AdminSmartRemoteController::class, 'virtualremote_blade_reg'])->name('admin-virtualremote-blade-reg');
            //リモコンデザイン検索>変更
            Route::post('virtualremote-blade/chg', [AdminSmartRemoteController::class, 'virtualremote_blade_chg'])->name('admin-virtualremote-blade-chg');
            //リモコンデザイン検索>削除
            Route::post('virtualremote-blade/del', [AdminSmartRemoteController::class, 'virtualremote_blade_del'])->name('admin-virtualremote-blade-del');
            //リモコンデザインチェック
            Route::get('virtualremote-blade/preview', [AdminSmartRemoteController::class, 'virtualremote_blade_preview'])->name('admin-virtualremote-blade-preview');
            //----------------------------------------------------------------------------------

            
            //----------------------------------------------------------------------------------
            //広告------------------------------------------------------------------------
            //----------------------------------------------------------------------------------
            //登録
            Route::get('adv/reg', [AdminAdvController::class, 'adv_regist'])->name('admin-adv-reg');
            Route::post('adv/reg', [AdminAdvController::class, 'adv_reg'])->name('admin-adv-reg');

            //検索
            Route::get('adv/search', [AdminAdvController::class, 'adv_search'])->name('admin-adv-search');
            //検索>変更
            Route::post('adv/search/chg', [AdminAdvController::class, 'adv_chg'])->name('admin-adv-chg');
            //検索>削除
            Route::post('adv/search/del', [AdminAdvController::class, 'adv_del'])->name('admin-adv-del');
            //----------------------------------------------------------------------------------

            
            //----------------------------------------------------------------------------------
            //通知------------------------------------------------------------------------
            //----------------------------------------------------------------------------------
            Route::get('notification/search', [AdminNotificationController::class, 'notification'])->name('admin-notification');
            //メール通知
            Route::post('notification/mail', [AdminNotificationController::class, 'admin_mail_send'])->name('admin-mail-send');
            //プッシュ通知
            Route::post('notification/push', [AdminNotificationController::class, 'admin_push_send'])->name('admin-push-send');
            //----------------------------------------------------------------------------------



            //----------------------------------------------------------------------------------
            //その他------------------------------------------------------------------------
            //----------------------------------------------------------------------------------
            //メモ検索
            Route::get('another/memo-search', [AdminAnotherController::class, 'memo_search'])->name('admin-memo-search');
            //検索>登録
            Route::post('another/memo-search/reg', [AdminAnotherController::class, 'memo_reg'])->name('admin-memo-reg');
            //検索>変更
            Route::post('another/memo-search/chg', [AdminAnotherController::class, 'memo_chg'])->name('admin-memo-chg');
            //検索>削除
            Route::post('another/memo-search/del', [AdminAnotherController::class, 'memo_del'])->name('admin-memo-del');
            //----------------------------------------------------------------------------------
        });
    });

    //ユーザー------------------------------------------------------------------------
    //プロフィール
    Route::get('profile/show', [UserController::class, 'profile_show'])->name('profile-show');
    Route::post('profile/change', [UserController::class, 'profile_change'])->name('profile-change');

    //要望・問い合わせ
    Route::get('request/show', [RequestController::class, 'request_show'])->name('request-show');
    Route::post('request/send', [RequestController::class, 'request_send'])->name('request-send');

    //フレンドリスト表示
    Route::get('friendlist', [FriendlistController::class, 'friendlist_show'])->name('friendlist-show');
    //フレンド申請
    Route::post('friend/request', [FriendlistController::class, 'friend_request'])->name('friend-request');
    //フレンド承認
    Route::post('friend/accept', [FriendlistController::class, 'friend_accept'])->name('friend-accept');
    //フレンド申請拒否
    Route::post('friend/decline', [FriendlistController::class, 'friend_decline'])->name('friend-decline');
    //フレンド申請キャンセル
    Route::post('friend/cancel', [FriendlistController::class, 'friend_cancel'])->name('friend-cancel');
    //フレンド情報表示
    Route::get('friend/show', [FriendlistController::class, 'friend_show'])->name('friend-show');

    //スマートリモコンリスト表示
    Route::get('smart-remote/show', [SmartRemoteController::class, 'remote_show'])->name('remote-show');
    
    //スマートリモコン登録
    Route::post('smart-remote/reg', [SmartRemoteController::class, 'remote_reg'])->name('remote-reg');
    //スマートリモコン詳細
    Route::get('smart-remote/show/detail', [SmartRemoteController::class, 'remote_show_detail'])->name('remote-show-detail');
    //スマートリモコン詳細変更
    Route::post('smart-remote/change', [SmartRemoteController::class, 'remote_change'])->name('remote-change');
    //スマートリモコン削除
    Route::post('smart-remote/del', [SmartRemoteController::class, 'remote_del'])->name('remote-del');
    //スマートリモコン共有解除
    Route::post('smart-remote/unshare', [SmartRemoteController::class, 'remote_unshare'])->name('remote-unshare');

    //スマートリモコン削除
    //Route::post('smart-remote/del', [SmartRemoteController::class, 'remote_del'])->name('remote-del');
    //デバイス登録
    Route::post('iotdevice/reg', [SmartRemoteController::class, 'iotdevice_reg'])->name('iotdevice-reg');

    //デバイス詳細
    Route::get('iotdevice/show/detail', [SmartRemoteController::class, 'iotdevice_show_detail'])->name('iotdevice-show-detail');
    

});

//PWA用マニフェストファイルを動的に生成
Route::get('/manifest.json', function () {
    $domain = env('DOMAINS');
    $app_name = env('APP_NAME');
    

    $manifest = [
        "name" => $domain === 'localhost' ? 'skcoco(検証)' : 'skcoco',
        "short_name" => $app_name,
        "description" => 'アプリリスト',
        "start_url" => $domain === 'localhost' ? "/other" : "/other",
        "display" => "standalone",
        "background_color" => "#ffffff",
        "theme_color" => "#000000",
        "icons" => [
            [
                "src" => "/other/img/icon/home_icon_192_192.png",
                "sizes" => "192x192",
                "type" => "image/png"
            ],
            [
                "src" => "/other/img/icon/home_icon_512_512.png",
                "sizes" => "512x512",
                "type" => "image/png"
            ]
        ]
    ];

    return response()->json($manifest)
        ->header('Content-Type', 'application/json');
});