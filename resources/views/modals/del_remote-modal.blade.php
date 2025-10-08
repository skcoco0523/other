

<div id="del_remote-modal" class="notification-overlay" onclick="closeModal('del_remote-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <form id="remoteDeleteForm" method="POST" action="{{ route('remote-del') }}" class="text-center">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">リモコン削除</h5>
                    <input type="hidden" name="remote_id" value="{{ $virtual_remote->remote_id ?? '' }}">
                    <input type="hidden" name="remote_user_id" value="{{ $virtual_remote->id ?? '' }}">
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">このリモコンを削除してよろしいですか？</label>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">
                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('del_remote-modal')">キャンセル</button>
                    <button id="del_button" type="submit" class="col-5 btn btn-danger">削除</button>
                </div>
            </form>
        </div>
    </div>
</div>