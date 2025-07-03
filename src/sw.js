import { precacheAndRoute } from 'workbox-precaching';

// プリキャッシュの設定
precacheAndRoute(self.__WB_MANIFEST);

// プッシュ通知の設定
self.addEventListener('push', function(event) {
    try {
        const data = event.data ? event.data.json() : {};

        self.registration.showNotification(data.title || '通知', {
            body: data.body || '',
            icon: data.icon || '/other/img/icon/home_icon_192_192.png',
            data: data.url || '/other',
            actions: [
                {
                    action: 'open_url',
                    title: 'Open'
                }
            ]
        });
        console.log('push event成功');
    } catch (error) {
        console.error('push event失敗:', error);
    }
});

// 通知クリック時の処理
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    if (event.notification.data) {
        event.waitUntil(
            clients.openWindow(event.notification.data)
        );
    }
});


// サービスワーカーのインストールとアクティブ化
self.addEventListener('install', event => {
    console.log('ServiceWorker インストールイベント');
    self.skipWaiting(); // インストール後すぐにアクティブ化
});

self.addEventListener('activate', event => {
    console.log('ServiceWorker アクティブ化イベント');
    event.waitUntil(clients.claim()); // アクティブ化後すぐにページを制御
});