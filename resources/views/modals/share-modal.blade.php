

<div id="share_modal" class="notification-overlay" onclick="closeModal('share_modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newPlaylistModalLabel">シェア</h5>
                <button type="button" class="btn-close close" aria-label="Close" onclick="closeModal('share_modal')"></button>
            </div>
            <div class="modal-body">
                <div class="share-icons">
                    <i class="fa-brands fa-line icon-50 share-button" style="color: #00c34d;" data-platform="line" alt="LINEで共有"></i>
                    <i class="fa-brands fa-x-twitter icon-50 share-button" style="color: #001c40;" data-platform="twitter" alt="Twitterで共有"></i>
                    <i class="fa-brands fa-facebook icon-50 share-button" style="color: #0863f7;" data-platform="facebook" alt="Facebookで共有"></i>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
