//シェアモーダル========================================================
//window.openModal = function openModal(modal_id, detail_id = null, url = null) {
window.openModal = function openModal(modal_id, params = {}) {
    var modal = document.getElementById(modal_id);

    if (!modal) {
        console.warn(`Modal with ID "${modal_id}" not found`);
        return; // モーダルが存在しなければ処理を中断
    }
    // フォームIDは直接プロパティに保持
    if (params.form_id) {
        modal._formId = params.form_id;
        console.log('modal._formId set:', modal._formId);
    }

    // params のキーに対応する要素に値をセット
    Object.keys(params).forEach(function(key) {
        const elements = modal.querySelectorAll("#" + key);
        elements.forEach(function(el) {
            if (el.tagName === "INPUT" || el.tagName === "TEXTAREA") {
                el.value = params[key];
            } else if (el.tagName === "H5" || el.tagName === "LABEL" || el.tagName === "BUTTON" || el.tagName === "SPAN" || el.tagName === "DIV") {
                el.textContent = params[key];
            }
        });
    });

    modal.style.display = 'block';
}

window.closeModal = function closeModal(modal_id) {
    // オーバーレイまたは閉じるボタンがクリックされた場合にのみモーダルを閉じる
    const modal = document.getElementById(modal_id);
    if (!modal) return;
    modal.style.display = 'none';
}

window.modalConfirm = function modalConfirm(modal_id) {
    // モーダルボタン処理
    const modal = document.getElementById(modal_id);
    if (!modal) return console.warn(`Modal with ID "${modal_id}" not found`);

    const form_id = modal._formId; // dataset ではなくプロパティから取得
    if (form_id) {
        const form = document.getElementById(form_id);
        if (form) form.submit(); // フォーム送信
    }else{
        console.warn(`form_id not set in modal dataset`);
    }
    closeModal(modal_id);
}
