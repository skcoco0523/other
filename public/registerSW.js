async function registerSW() {
    if ('serviceWorker' in navigator) {
        try {
            // 不要なサービスワーカーを削除（必要な場合のみ）
            const registrations = await navigator.serviceWorker.getRegistrations();
            for (const reg of registrations) {
                const isUnregistered = await reg.unregister();
                console.log(isUnregistered ? 'ServiceWorker 登録解除: ' + reg.scope : 'ServiceWorker 登録解除失敗: ' + reg.scope);
            }

            // サービスワーカーを登録 スコープを /app01/ に設定
            console.log('ServiceWorker 登録開始');
            registration = await navigator.serviceWorker.register('/app01/build/sw.js', {
                scope: '/app01/'
            });
            console.log('ServiceWorker 登録成功: ', registration.scope);

            return registration;
        } catch (error) {
            console.log('ServiceWorker 処理失敗: ', error);
        }
    } else {
        console.log('ServiceWorker 未対応のブラウザ');
    }
    return false;

}