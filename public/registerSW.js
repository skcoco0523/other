async function registerSW() {
    if ('serviceWorker' in navigator) {
        try {
            // 不要なサービスワーカーを削除（必要な場合のみ）
            const registrations = await navigator.serviceWorker.getRegistrations();
            for (const reg of registrations) {
                // 1. Push通知のサブスクリプションがあれば解除
                const subscription = await reg.pushManager.getSubscription();
                if (subscription) {
                    const unsubscribed = await subscription.unsubscribe();
                    console.log(unsubscribed ? 'Push解除成功: ' + subscription.endpoint : 'Push解除失敗');
                }

                // 2. サービスワーカー自体を解除
                const isUnregistered = await reg.unregister();
                console.log(isUnregistered ? 'ServiceWorker 登録解除: ' + reg.scope : 'ServiceWorker 登録解除失敗: ' + reg.scope);
            }

            // サービスワーカーを登録 スコープを /app01/ に設定
            console.log('ServiceWorker 登録開始');
            const registration = await navigator.serviceWorker.register('/app01/build/sw.js', {
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