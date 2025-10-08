

<div id="chg_remote_name-modal" class="notification-overlay" onclick="closeModal('chg_remote_name-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <form id="remoteNameChangeForm" method="POST" action="{{ route('remote-change') }}" class="text-center">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">リモコン名変更</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="remote_id" value="{{ $virtual_remote->remote_id ?? '' }}">
                        <input type="hidden" name="remote_user_id" value="{{ $virtual_remote->id ?? '' }}">
                        <input type="text" class="form-control form-control-sm d-inline-block w-auto" name="remote_name" value="{{ $virtual_remote->name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">このリモコン名を変更してよろしいですか？</label>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('chg_remote_name-modal')">キャンセル</button>
                    <button id="del_button" type="submit" class="col-5 btn btn-danger">変更</button>
                </div>
            </form>
        </div>
    </div>
</div>