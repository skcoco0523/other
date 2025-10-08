

<div id="unshare_remote-modal" class="notification-overlay" onclick="closeModal('unshare_remote-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <form id="remotUnShareForm" method="POST" action="{{ route('remote-unshare') }}" class="text-center">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">リモコンの共有解除</h5>
                    <input type="hidden" name="remote_id" value="{{ $virtual_remote->remote_id ?? '' }}">
                    <input type="hidden" name="remote_user_id" value="{{ $virtual_remote->id ?? '' }}">
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">このリモコンの所有権限を解除してよろしいですか？</label>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('unshare_remote-modal')">キャンセル</button>
                    <button id="del_button" type="submit" class="col-5 btn btn-danger">解除</button>
                </div>
            </form>
        </div>
    </div>
</div>