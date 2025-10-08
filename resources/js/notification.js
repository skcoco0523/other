window.showNotification = function showNotification(message_org, type, sec) {
    const notification = document.getElementById('notification');
    //アイコン情報：https://fontawesome.com/

    let message = message_org.replace(/\n/g, '<br>');
    notification.innerHTML = "";
    switch(type){
        case "loading":     //スピナー
                notification.innerHTML += `
                <div class="d-flex flex-column align-items-center">
                    <div id="spinnerSection" class="spinner-border mt-2" role="status" aria-hidden="true"></div>
                </div>
                <div id="messageSection" style="display: none;">
                    <strong>${message}</strong>
                </div>
                `;
            break;
        case "dev_add":    //デバイス追加
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-computer fa-bounce red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "dev_del":    //デバイス削除
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-computer fa-shake red "></i></div>
                                        <p>${message}</p>`;

        case "remote_add":    //リモコン追加
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-mobile fa-bounce red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "remote_del":    //リモコン削除
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-mobile fa-shake red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "remote_chg":    //リモコン変更
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-mobile fa-fade red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "category_add":    //カテゴリ登録
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-icons fa-bounce red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "category_del":    //カテゴリ削除
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-icons fa-shake red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "profile":    //プロフィール変更
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-address-card fa-fade red "></i></div>
                                        <p>${message}</p>`;
            break; 
        case "send":    //送信
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-envelope fa-fade red "></i></div>
                                        <p>${message}</p>`;
            break; 
        case "friend":    //フレンド関連
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-user-group fa-fade red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "mypl_create":    //マイプレイリスト作成
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-list-ul fa-bounce red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "mypl_chg":    //マイプレイリスト変更
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-list-ul fa-fade red "></i></div>
                                        <p>${message}</p>`;
            break; 
        case "mypl_del":    //削除
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-list-ul fa-shake red "></i></div>
                                        <p>${message}</p>`;
            break;
        case "error":    //エラー
            notification.innerHTML += `<div class="icon-50"><i class="fa-solid fa-triangle-exclamation fa-shake red "></i></div>
                                        <p>${message}</p>`;
            break;
        

        default:            //メッセージのみ
            notification.innerHTML += `<p>${message}</p>`;
            break;
    }
    notification.style.display = 'block';

    if (type === "loading") {
        // 半分の時刻が経過したらスピナーを非表示にしてメッセージを表示
        setTimeout(() => {
            document.getElementById('spinnerSection').style.display = 'none';
            document.getElementById('messageSection').style.display = 'block';
        }, sec / 2);
    }

    // 指定された秒数後に通知を非表示にする
    setTimeout(hideNotification, sec);
}

window.hideNotification = function hideNotification() {
    document.getElementById('notification').style.display = 'none';
}
