

<div id="add_note-modal" class="notification-overlay" onclick="closeModal('add_note-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- 新規メモ登録フォーム -->
            <form action="{{ route('note-reg') }}" method="POST">
                @csrf
                <input type="hidden" name="check_flag" value="false" >
                <div class="modal-header">
                    <h5 class="modal-title" id="newIotDeviceModalLabel">新規メモ登録</h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeModal('add_note-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="note_title" class="form-label">タイトル</label>
                        <input type="text" class="form-control" id="note_title" name="title" placeholder="メモ名を入力" required>
                    </div>
                    <div class="mb-3">
                        <label for="color_num" class="form-label">背景色</label>
                        <div class="d-flex align-items-center">
                            <select class="form-select" id="color_num" name="color_num" style="flex-grow: 1;">
                                @foreach(config('common.note_colors') as $key => $color)
                                    {{-- 各選択肢に背景色を直接指定 --}}
                                    <option value="{{ $key }}" data-code="{{ $color['code'] }}" style="background-color: {{ $color['code'] }};">
                                        {{ $color['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="note_content" class="form-label">内容</label>
                        <textarea class="form-control" id="note_content" name="content" rows="5" placeholder="メモ内容を入力" required></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('add_note-modal')">キャンセル</button>
                    <button id="note_add_confirm_button" type="submit" class="col-5 btn btn-danger disabled">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    var noteTitleInput = document.getElementById('note_title');
    var noteContentInput = document.getElementById('note_content');
    var confirmButton = document.getElementById('note_add_confirm_button');
    function checkInput() {
        // 入力がある場合は保存ボタンを有効にする
        //if (noteTitleInput.value.trim() !== '' && noteContentInput.value.trim() !== '') 
        if (noteTitleInput.value.trim() !== '') 
            confirmButton.classList.remove('disabled');
        else
            confirmButton.classList.add('disabled');
    }
    // 入力が変更されたときにチェックする
    noteTitleInput.addEventListener('input', checkInput);
    noteContentInput.addEventListener('input', checkInput);
    // 初期状態をチェックする
    checkInput();

    document.getElementById('color_num').addEventListener('change', function() {
        // 選択されたoptionからdata-code（16進数カラーコード）を取得
        const selectedOption = this.options[this.selectedIndex];
        const colorCode = selectedOption.getAttribute('data-code');
        
        // プレビューボックスの背景色を更新
        document.getElementById('color_num').style.backgroundColor = colorCode;
    });

});
</script>