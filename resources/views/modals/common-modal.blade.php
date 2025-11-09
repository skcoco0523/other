{{--共通モーダル--}}
<div id="common-modal" class="notification-overlay" onclick="closeModal('common-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title" style="min-width:1ch;min-height:1em;">&nbsp;</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label id="mess" class="form-label" style="min-width:1ch;min-height:1em;">&nbsp;</label>
                </div>
            </div>
            <div class="modal-footer row gap-3 justify-content-center">
                <button type="button" class="col-5 btn btn-secondary" id="cancel_btn" onclick="closeModal('common-modal')" style="min-width:2ch;min-height:1em;">&nbsp;</button>
                <button type="button" class="col-5 btn btn-danger" id="confirm_btn" onclick="modalConfirm('common-modal')" style="min-width:2ch;min-height:1em;">&nbsp;</button>
            </div>
        </div>
    </div>
</div>