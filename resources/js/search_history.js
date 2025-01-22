window.redirectToSearch = function redirectToSearch(keyword) {
    window.location.href = searchListShowUrl + "?keyword=" + encodeURIComponent(keyword);
}

window.delete_history = function delete_history() {
    // お気に入りの状態を取得するためのHTTPリクエストを行う
    $.ajax({
        //POST通信
        type: "post",
        url: historyDeleteUrl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {},
    })
    .done(response => {
        console.log(response);
        showNotification('検索履歴を削除しました。', "", 1000);
        // 1秒後にリロード
        setTimeout(() => {location.reload();}, 1000);
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