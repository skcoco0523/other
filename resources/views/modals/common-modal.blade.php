{{--共通モーダル--}}
<div id="common-modal" class="notification-overlay" onclick="closeModal('common-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title" style="min-width:1ch;min-height:1em;">&nbsp;</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label id="mess" class="form-label" style="min-width:1ch;min-height:1em;white-space:pre-line;">&nbsp;</label>
                    <div id="user_chk_area" style="display:none;" class="mt-3">
                        <input type="checkbox" id="user_chk_box">
                        <label for="user_chk_box">確認しました</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer row gap-3 justify-content-center">
                <button type="button" class="col-5 btn btn-secondary" id="cancel_btn" onclick="closeModal('common-modal')" style="min-width:2ch;min-height:1em;">&nbsp;</button>
                <button type="button" class="col-5 btn btn-danger" id="confirm_btn" onclick="modalConfirm('common-modal')" style="min-width:2ch;min-height:1em;">&nbsp;</button>
            </div>
        </div>
    </div>
</div>
