

<div id="edit_pl_modal" class="notification-overlay" onclick="closeModal('edit_pl_modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- プレイリスト名変更フォーム -->
            <form action="{{ route('myplaylist-chg') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">プレイリスト名変更</h5>
                    <input type="hidden" name ="id" value={{$detail_id}}>
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeModal('edit_pl_modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">プレイリスト名</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$name}}" placeholder="プレイリスト名を入力" required>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('edit_pl_modal')">キャンセル</button>
                    <button id="chg_button" type="submit" class="col-5 btn btn-danger disabled">変更</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    var playlistNameInput = document.getElementById('name');
    var saveBchg_buttonutton = document.getElementById('chg_button');
    function checkInput() {
        // 入力がある場合は保存ボタンを有効にする
        if (playlistNameInput.value.trim() !== '') 
            chg_button.classList.remove('disabled');
        else
            chg_button.classList.add('disabled');
    }
    // 入力が変更されたときにチェックする
    playlistNameInput.addEventListener('input', checkInput);
    // 初期状態をチェックする
    checkInput();
});
</script>