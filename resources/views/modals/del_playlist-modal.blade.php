

<div id="del_pl_modal" class="notification-overlay" onclick="closeModal('del_pl_modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- プレイリスト名変更フォーム -->
            <form action="{{ route('myplaylist-del') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">プレイリスト削除</h5>
                    <input type="hidden" name ="id" value={{$detail_id}}>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">このプレイリストを削除してよろしいですか？</label>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('del_pl_modal')">キャンセル</button>
                    <button id="del_button" type="submit" class="col-5 btn btn-danger">削除</button>
                </div>
            </form>
        </div>
    </div>
</div>