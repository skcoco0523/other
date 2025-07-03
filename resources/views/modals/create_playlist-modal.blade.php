

<div id="create_pl_modal" class="notification-overlay" onclick="closeModal('create_pl_modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- 新規プレイリスト作成フォーム -->
            <form action="{{ route('myplaylist-reg') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">新規プレイリスト作成</h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeModal('create_pl_modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pl_name" class="form-label">プレイリスト名</label>
                        <input type="text" class="form-control" id="pl_name" name="name" placeholder="プレイリスト名を入力" required>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('create_pl_modal')">キャンセル</button>
                    <button id="save_button" type="submit" class="col-5 btn btn-danger disabled">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    var playlistNameInput = document.getElementById('pl_name');
    var saveBsave_buttonutton = document.getElementById('save_button');
    function checkInput() {
        // 入力がある場合は保存ボタンを有効にする
        if (playlistNameInput.value.trim() !== '') 
            save_button.classList.remove('disabled');
        else
            save_button.classList.add('disabled');
    }
    // 入力が変更されたときにチェックする
    playlistNameInput.addEventListener('input', checkInput);
    // 初期状態をチェックする
    checkInput();
});
</script>