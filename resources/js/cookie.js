window.setCookie = function setCookie(name, value, days, path = '/') {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000)); // 期限を設定
    document.cookie = name + "=" + encodeURIComponent(value) + 
                      "; expires=" + expires.toUTCString() + 
                      "; path=" + path;
}

window.getCookie = function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length); // 空白を削除
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length)); // 値をデコードして返す
    }
    return null; // クッキーが見つからない場合は null を返す
}