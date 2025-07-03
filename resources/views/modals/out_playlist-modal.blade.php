

<div id="out_pl_modal" class="notification-overlay" onclick="closeModal('out_pl_modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- プレイリストから曲削除フォーム -->
            <form action="{{ route('myplaylist-detail-fnc') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">プレイリストから削除</h5>
                    <input type="hidden" name="fnc" value='del'>
                    <input type="hidden" name="id" value={{$pl_id}}>
                    <?//openModal()の引数で指定する?>
                    <input type="hidden" id="url" name="url">
                    <input type="hidden" id="detail_id" name="detail_id">
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">この曲をプレイリストから削除してよろしいですか？</label>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('out_pl_modal')">キャンセル</button>
                    <button id="del_button" type="submit" class="col-5 btn btn-danger">削除</button>
                </div>
            </form>
        </div>
    </div>
</div>