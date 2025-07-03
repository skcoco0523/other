//シェアモーダル========================================================
window.openModal = function openModal(modal_id, detail_id = null, url = null) {
    var modal = document.getElementById(modal_id);

    //シェア処理のみ
    if(modal_id=='share_modal'){
        var shareButtons = modal.querySelectorAll('.share-button');
        shareButtons.forEach(function(button) {
            var platform = button.getAttribute('data-platform');
            button.setAttribute('onclick', "shareToPlatform('" + platform + "', '" + url + "')");
        });
    }
    //detail_id,url があれば、埋め込む　　 myplへの追加などで引き渡す
    if(detail_id){
        const detailInput = modal.querySelector("#detail_id");
        if (detailInput) detailInput.value = detail_id;
    }    
    if(url){
        const urlInput = modal.querySelector("#url");
        if (urlInput) urlInput.value = url;
    }
    //playlist追加モーダルのみ
    //if(modal_id=='add_pl_modal'){
    //    get_myplaylist();
    //}

    modal.style.display = 'block';
}

window.closeModal = function closeModal(modal_id) {
    // オーバーレイまたは閉じるボタンがクリックされた場合にのみモーダルを閉じる
    //if (event.target.classList.contains('notification-overlay') || event.target.classList.contains('close')) {
        document.getElementById(modal_id).style.display = 'none';
    //}
}


window.shareToPlatform = function shareToPlatform(platform, url) {

    let popupUrl;
    const width = 600;
    const height = 400;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);

    switch(platform) {
        case 'line':
            popupUrl = 'https://social-plugins.line.me/lineit/share?url=' + encodeURIComponent(url);
            break;
        case 'twitter':
            popupUrl = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url);
            break;
        case 'facebook':
            popupUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
            break;
        default:
            return;
    }

    window.open(popupUrl, platform + 'Share', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    closeModal('share_modal'); // モーダルを閉じる
}
