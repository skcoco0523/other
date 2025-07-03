

<div id="add_virtualremote-modal" class="notification-overlay" onclick="closeModal('add_virtualremote-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- 新規プレイリスト作成フォーム -->
            <form action="{{ route('iotdevice-reg') }}" method="POST">
                @csrf
                <input type="hidden" name="check_flag" value="false" >
                <div class="modal-header">
                    <h5 class="modal-title" id="newvirtualremoteModalLabel">新規リモコン登録</h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeModal('add_virtualremote-modal')"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="remote_kind" class="form-label">リモコンタイプ</label>
                        <select name="remote_kind" id="remote_kind_select" class="form-control">
                            <option value="">タイプを選択</option>
                            @foreach (config('common.remote_kind') as $key => $value)
                                <option value="{{ $value }}">{{ $key }}</option>
                            @endforeach
                        </select>
            
                    </div>

                    <div class="mb-3">
                        <label for="remote_name" class="form-label">リモコン名</label>
                        <input type="text" class="form-control" id="remote_name" name="remote_name" placeholder="リモコン名を入力" required>
                    </div>

                    
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('add_virtualremote-modal')">キャンセル</button>
                    <button id="save_button" type="submit" class="col-5 btn btn-danger disabled">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    var lastSelectedKind;
    var remoteKindSelect        = document.getElementById('remote_kind_select');
    var virtualremoteNameInput  = document.getElementById('remote_name');
    var saveButton = document.getElementById('save_button');
    async function checkInput() {
        // 入力がある場合は保存ボタンを有効にする
        //if (virtualremoteKindInput.value.trim() !== '' && virtualremoteNameInput.value.trim() !== '') 
        if (virtualremoteNameInput.value.trim() !== '') 
            saveButton.classList.remove('disabled');
        else
            saveButton.classList.add('disabled');

        //リモコンの種別が選択されたら、使用可能なデザインを取得する
        if (remoteKindSelect.value.trim() !== ''){
            if (lastSelectedKind !== remoteKindSelect) { // 修正点: currentSelectedKi
                // 選択されたkindを保存
                lastSelectedKind = remoteKindSelect.value.trim(); // 修正点: var を付けずに既存の lastSelectedKind に代入
                console.log("lastSelectedKind",lastSelectedKind);

                try {
                    const blade_list = await get_virtualremote_blade(lastSelectedKind);
                    //console.log(blade_list);

                    // ここにblade_listを使ってUIを更新するロジックが必要ですが、
                    // 「必要最低限修正」の指示に従い、元のコードに存在しないUI更新ロジックは追加しません。
                    // もし、取得したデータを表示したい場合は、別途そのロジックを追記する必要があります。

                } catch (error) {
                    console.error('リモコンブレード取得エラー:', error); // エラーログを修正
                    // エラー時のUI処理（例：アラート表示など）
                }
            }
        }
    }
    // 入力が変更されたときにチェックする
    remoteKindSelect.addEventListener('input', checkInput);
    virtualremoteNameInput.addEventListener('input', checkInput);
    // 初期状態をチェックする
    checkInput();
});

async function get_virtualremote_blade(remote_kind) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "get",
            url: getVirtualRemoteBladeUrl,
            headers: {
                //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //'Authorization': 'Bearer ' + apiToken
            },
            data: {search_kind: remote_kind },
        })
        .done(data => {
            if (data && data.length > 0) {
                resolve(data);  // 成功時はresolveで結果を返す
            } else {
                resolve([]);  // データがない場合
            }
        })
        .fail((xhr, status, error) => {
            console.error('Error fetching advertisement:', error);
            reject(error);  // 失敗時はrejectでエラーを返す
        });
    });
};

</script>