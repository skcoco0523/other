window.setCustomCategoryActions = function setCustomCategoryActions(bit_num, status) {
    if (typeof customCategoryActions === 'undefined') {
        window.customCategoryActions = {}; // グローバルオブジェクトを定義
    }
    customCategoryActions[bit_num] = status ? "del" : "add";

}

window.chgToCustomCategory = function chgToCustomCategory(music_id, bit_num) {
    // アイコンのdata属性を取得
    const customCategorySelector = `[bit_num="${bit_num}"]`;
    
    // クリックイベントを無効化する
    document.querySelectorAll(customCategorySelector).forEach(icon => {
        icon.onclick = null;
    });
    
    //console.log("music_id:",music_id,"  bit_num:",bit_num,"  type:",customCategoryActions[bit_num]);
    // お気に入りの状態を取得するためのHTTPリクエストを行う
    $.ajax({
        //POST通信
        type: "post",
        url: customCategoryChangeUrl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { music_id: music_id, bit_num: bit_num, type: customCategoryActions[bit_num] },
    })
    .done(response => {
        if (response === "add") {
            showNotification('カテゴリに追加しました。', "category_add", 2000);
            document.querySelectorAll(customCategorySelector).forEach(icon => {
                // アイコン切り替え
                icon.classList.add("active");
            });
            customCategoryActions[bit_num] = "del";
        } else if (response === "del") {
            showNotification('カテゴリから削除しました。', "category_del", 2000);
            document.querySelectorAll(customCategorySelector).forEach(icon => {
                // アイコン切り替え
                icon.classList.remove("active");
            });
            customCategoryActions[bit_num] = "add";
        } else {
            showNotification("カテゴリの切り替えに失敗しました。", "", 1000);
        }
        
    })
    .always(() => {
        // 一定時間後にクリックイベントを再度有効化する
        setTimeout(() => {
            document.querySelectorAll(customCategorySelector).forEach(icon => {
                icon.onclick = () => chgToCustomCategory(music_id, bit_num);
            });
        }, 2000); // 2000ミリ秒（2秒）後に再設定する例
    })
    .fail((xhr, status, error) => {
        if (xhr.status === 401) {
            // 認証エラーの場合の処理
            showNotification('ログインが必要です。', "", 1000);
            // 1秒後にログインページにリダイレクト
            setTimeout(() => {window.location.href = loginUrl;}, 1000);
        } else {
            // その他のエラーが発生した場合の処理
            console.error('エラー:', error);
            showNotification('エラーが発生しました。もう一度試してください。', "", 1000);
        }
    });
}
