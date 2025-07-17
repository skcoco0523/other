

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
                        {{-- このプルダウンにJavaScriptでデザイン名を追加します --}}
                        <select name="id" id="blade_select" class="form-control">
                            <option value="">デザインを選択</option>
                            {{-- ここにAPIから取得したリモコンデザインのオプションが動的に追加されます --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <div id="remote_preview_area" style="border: 1px solid #ccc; padding: 10px; min-height: 150px; background-color: #f9f9f9; max-height: 300px; overflow: auto;">
                            <p style="text-align: center; color: #888;">デザインを選択するとプレビューが表示されます</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remote_name" class="form-label">リモコン名</label>
                        <input type="text" class="form-control" id="remote_name" name="remote_name" placeholder="リモコン名を入力" required>
                    </div>

                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('add_virtualremote-modal')">キャンセル</button>
                    <button id="remote_save_button" type="submit" class="col-5 btn btn-danger disabled">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    var lastSelectedKind; // 最後に選択されたリモコンタイプを保持
    var remoteKindSelect = document.getElementById('remote_kind_select');
    var virtualremoteNameInput = document.getElementById('remote_name');
    var bladeNameSelect = document.getElementById('blade_select'); // リモコンデザイン選択プルダウン
    var remotePreviewArea = document.getElementById('remote_preview_area'); // プレビュー表示領域
    var saveButton = document.getElementById('remote_save_button');

    function checkInput() {
        // リモコン名が入力されていれば保存ボタンを有効にする
        if (virtualremoteNameInput.value.trim() !== '' && bladeNameSelect.value.trim() !== '') {
            console.log("ture");
            saveButton.classList.remove('disabled');
            console.log(saveButton);
        } else {
            console.log("false");
            saveButton.classList.add('disabled');
            console.log(saveButton);
        }
    }

    // リモコンタイプ選択時のイベントリスナー
    // `<select>`要素の変更は 'change' イベントで検知するのが適切
    remoteKindSelect.addEventListener('change', async function() {
        const currentSelectedKind = this.value.trim(); // 現在選択された値

        // リモコンの種別が選択されており、かつ前回の選択と異なる場合のみAPIを呼び出す
        if (currentSelectedKind !== '' && lastSelectedKind !== currentSelectedKind) {
            // 選択されたkindを保存
            lastSelectedKind = currentSelectedKind;
            console.log("lastSelectedKind:", lastSelectedKind);

            bladeNameSelect.innerHTML = '<option value="">デザインを選択</option>'; // デザインプルダウンをクリア
            remotePreviewArea.innerHTML = '<p style="text-align: center; color: #888;">デザインを選択するとプレビューが表示されます</p>'; // プレビューをクリア

            try {
                // APIからリモコンデザインのリストを取得
                // dataは {id: ..., html_content: "<div...>...</div>"} の配列を期待
                const designList = await get_virtualremote_blade(lastSelectedKind);

                if (designList && designList.length > 0) {
                    
                    designList.forEach((design, index) => {
                        const option = document.createElement('option');
                        option.value = design.id;
                        option.textContent = (index + 1);
                        //option.dataset.id = design.id;
                        option.dataset.html = design.html_content;
                        bladeNameSelect.appendChild(option);
                    });
                } else {
                    bladeNameSelect.innerHTML = '<option value="">利用可能なデザインがありません</option>';
                }
            } catch (error) {
                console.error('リモコンデザインリストの取得エラー:', error); // エラーログを修正
                alert('リモコンデザインの取得中にエラーが発生しました。'); // ユーザーへのアラート
            }
        } else if (currentSelectedKind === '') {
            // タイプが未選択に戻された場合、プルダウンとプレビューをリセット
            bladeNameSelect.innerHTML = '<option value="">デザインを選択</option>';
            remotePreviewArea.innerHTML = '<p style="text-align: center; color: #888;">デザインを選択するとプレビューが表示されます</p>';
            lastSelectedKind = undefined; // 選択状態をリセット
        }
    });

    // リモコン名入力時のイベントリスナー
    virtualremoteNameInput.addEventListener('input', checkInput);
    bladeNameSelect.addEventListener('input', checkInput);

    // 2. リモコンデザイン選択時のイベントリスナー (プレビュー表示のため)
    bladeNameSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex]; // 選択された <option> 要素を取得

        if (selectedOption && selectedOption.value !== '') {
            // data-html 属性からHTMLコンテンツを取得
            const htmlContent = selectedOption.dataset.html; 
            
            if (htmlContent) {
                remotePreviewArea.innerHTML = htmlContent; // プレビュー領域にHTMLを挿入

            } else {
                remotePreviewArea.innerHTML = '<p style="text-align: center; color: #e74c3c;">プレビューのHTMLがデータに含まれていません。</p>';
            }
        } else {
            remotePreviewArea.innerHTML = '<p style="text-align: center; color: #888;">デザインを選択するとプレビューが表示されます</p>';
        }
    });

    // 初期状態をチェックする
    checkInput();
    // ページロード時に、もし remoteKindSelect に初期値があれば、それを元にデザインリストをロードする
    if (remoteKindSelect.value.trim() !== '') {
        remoteKindSelect.dispatchEvent(new Event('change')); // changeイベントをトリガー
    }
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
                console.log("data:", data);
                resolve(data);  // 成功時はresolveで結果を返す
            } else {
                console.log("blade_nothing");
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