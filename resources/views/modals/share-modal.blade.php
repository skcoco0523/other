<div id="share-modal" class="notification-overlay" onclick="closeModal('share-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">シェア</h5>
                <button type="button" class="btn-close close" aria-label="Close" onclick="closeModal('share-modal')"></button>
            </div>
            <div class="modal-body">
                <!-- ここに params.url を埋め込む hidden input -->
                <input type="hidden" id="url" value="">

                <div class="share-icons">
                    <i class="fa-brands fa-line icon-50 share-button"       style="color: #00c34d;" onclick="shareButtonClick('line')"></i>
                    <i class="fa-brands fa-x-twitter icon-50 share-button"  style="color: #001c40;" onclick="shareButtonClick('twitter')"></i>
                    <i class="fa-brands fa-facebook icon-50 share-button"   style="color: #0863f7;" onclick="shareButtonClick('facebook')"></i>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script>
    // share-modal 固有のクリック処理はここに書く
    function shareButtonClick(platform) {
        const url = document.getElementById('url')?.value;
        if(!url) return;

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
        closeModal('share-modal');
    }
</script>