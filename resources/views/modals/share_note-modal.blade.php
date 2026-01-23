

<div id="share_note-modal" class="notification-overlay" onclick="closeModal('share_note-modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <?//処理が複雑になるため、フォームではなくAPI?>
        <div class="modal-content">
            <div class="modal-header mx-auto w-100 overflow-hidden">
                <input type="hidden" id="note_id" value="">
                <h5 class="modal-title text-ellipsis" id="note_title"></h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="closeModal('share_note-modal')"></button>
            </div>
            <div class="modal-body">

                <?//共有リスト?>
                <div class="mb-3">
                    <table class="table table-borderless table-center" style="table-layout: fixed;">
                        <colgroup>
                            <col style="width: 60%">
                            <col style="width: 40%">
                        </colgroup>
                        <tbody id="share_frined_list_area">
                            <?//共有メモリストをここに動的に追加?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer row gap-3 justify-content-center">
                <button type="button" id="cancel_btn" class="col-5 btn btn-secondary" onclick="closeModal('share_note-modal')">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        var noteId                      = document.getElementById('note_id');                    // メモID
        var noteTitle                   = document.getElementById('note_title');                // メモタイトル
        var shareFrinedListArea         = document.getElementById('share_frined_list_area');      // 共有リスト表示領域

        var get_share_note_list_flag    = false; // デバイス取得済みフラグ
        const modal                     = document.getElementById('share_note-modal');

        //モーダル表示時：
        modal.addEventListener('modal:open', async function () {
            if(get_share_note_list_flag)    return; // 既に取得済みなら再取得しない
            if(noteId.value == '')          return; // メモIDがない場合は処理しない
            await refreshShareFriendList();

        });

        //編集モーダル非表示時：
        modal.addEventListener('modal:close', () => {});

    });

    // フレンドリストエリアの更新
    async function refreshShareFriendList() {
        const noteId = document.getElementById('note_id').value;
        const shareFrinedListArea = document.getElementById('share_frined_list_area');
        const noteTitle = document.getElementById('note_title');

        try {
            const noteShareFriends = await get_friend_share_status(noteId);

            // エリアをリセット
            shareFrinedListArea.innerHTML = '';

            if (noteShareFriends && noteShareFriends.length > 0) {
                noteShareFriends.forEach((friend) => {
                    const isShared = friend.is_shared;
                    const friendId = friend.friend_id;
                    //const noteId = friend.note_id;
                    const noteShareId = friend.note_share_id;
                    const btnClass = isShared ? 'btn-danger' : 'btn-primary';
                    const btnText = isShared ? '解除' : '共有';
                    const shareAction  = isShared ? 'unshare' : 'share';
                    
                    // 2. 権限トグルの生成 (共有中のみ表示)
                    let permissionToggle = '';
                    if (isShared) {
                        const adminFlag   = friend.admin_flag; 
                        const isChecked   = adminFlag ? 'checked' : '';
                        const toggleLabel = adminFlag ? '編集可' : '閲覧のみ';
                        const editAction  = adminFlag ? 'disable_edit' : 'enable_edit';
                        
                        const labelStyle = adminFlag 
                            ? 'font-size: 11px; font-weight: bold; color: #198754; line-height: 1;' 
                            : 'font-size: 10px; color: #6c757d; opacity: 0.8; line-height: 1;';

                        permissionToggle = `
                            <div class="d-inline-flex flex-column align-items-center justify-content-center align-self-center me-2" 
                                style="width: 55px; min-height: 38px; vertical-align: middle;">
                                <label class="cursor-pointer mb-1 text-nowrap" for="toggle_${friendId}" style="${labelStyle}">
                                    ${toggleLabel}
                                </label>
                                <div class="form-check form-switch m-0 p-0 d-flex align-items-center" style="min-height: auto;">
                                    <input class="form-check-input cursor-pointer m-0" type="checkbox" role="switch" 
                                        id="toggle_${friendId}" ${isChecked} onclick="changeNoteShare('${editAction}', ${friendId}, ${noteId}, ${noteShareId})">
                                </div>
                            </div>
                        `;
                    }
                    
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-start align-middle text-truncate" style="max-width: 0;">${friend.name}</td>
                        <td class="text-end">
                            <button type="button" 
                                class="btn btn-sm ${btnClass}" onclick="changeNoteShare('${shareAction}', ${friendId}, ${noteId}, ${noteShareId})"> ${btnText}
                            </button>
                            ${permissionToggle}
                        </td>
                    `;
                    
                    shareFrinedListArea.appendChild(tr);
                });
            } else {
                shareFrinedListArea.innerHTML = '<tr><td colspan="2" class="text-center">フレンドがいません</td></tr>';
            }
        } catch (err) {
            console.error(err);
            alert('共有状況の更新に失敗しました。');
        }
    }



    //==================================================================
    //API
    //==================================================================
    // 共有メモの共有者取得
    async function get_friend_share_status(note_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "get",
                url: getFriendlistUrl,
                headers: {},
                //data: {},
                data: {note_share_status:1, note_id:note_id},
            })
            .done(data => {
                if (data && data.length > 0)    resolve(data);  // 成功時はresolveで結果を返す
                else                            resolve([]);  // データがない場合
            })
            .fail((xhr, status, error) => {
                console.error('Error fetching advertisement:', error);
                reject(error);  // 失敗時はrejectでエラーを返す
            });
        });
    };

    // 共有状態切り替え
    async function changeNoteShare(action, friend_id, note_id, note_share_id) {
        console.log('changeNoteShare', action, friend_id, note_id, note_share_id);
        
        let apiUrl;
        if(action === 'share'){
            apiUrl = shareNoteWithFriendUrl;
        }else if(action === 'unshare'){
            apiUrl = unshareNoteFromFriendUrl;
        }else if(action === 'enable_edit'){
            apiUrl = enableEditForSharedNoteUrl;
        }else if(action === 'disable_edit'){
            apiUrl = disableEditForSharedNoteUrl;
        }   

        console.log('apiUrl', apiUrl);

        try {
            // 2. $.ajax 自体を await することで、サーバーの処理完了を待つ
            await $.ajax({
                type: "post",
                url: apiUrl,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { friend_id: friend_id, note_id: note_id, note_share_id: note_share_id },
                
            }).done(data => {
                console.log('API Response:', data);
            }).fail((xhr, status, error) => {
                console.error('API Error:', error);
                alert('共有状態の変更に失敗しました。');
            });

            // サーバー側の処理が完全に終わってから再描画
            await refreshShareFriendList();

        } catch (err) {
            console.error('System Error:', err);
        }
    }


</script>