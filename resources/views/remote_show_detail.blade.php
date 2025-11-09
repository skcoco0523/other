@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="remote-header d-flex flex-column align-items-end mb-3">
        
        <?//設定ボタンを一番上（右端）に配置?>
        <button type="button" class="btn btn-secondary btn-sm mb-2" id="toggleEditModeBtn">
            <i class="fa-solid fa-gear"></i> <span id="buttonText">設定</span>
        </button>

        <div class="title-text mx-auto remote-name-display-edit-area w-100"><?//w-100で親の幅全体?>
            <?//表示モード?>
            <div id="DisplayArea">
                <h3 class="mb-0 text-center">{{ $virtual_remote->name ?? 'リモコン' }}</h3>
            </div>
            <div id="EditArea" style="display: none;">
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-2">
                    <?// 編集モード（最初は非表示）?>
                    <?// 変更権限がある場合?>
                    @if($virtual_remote->admin_flag ?? false)
                        <input type="text" id="remoteNameInput" class="form-control form-control-sm w-auto" value="{{ $virtual_remote->name ?? '' }}" readonly>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('chg_remote_name-modal');">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    @endif
                    
                    @if(($virtual_remote->admin_user_id ?? 0) == Auth::id())
                        <?// 所有者のみ削除可能?>
                        <button type="button" class="btn btn-danger btn-sm" onclick="openModal('del_remote-modal');">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    @else
                        <?// 所有者でない場合は共有解除?>
                        <button type="button" class="btn btn-danger btn-sm" onclick="openModal('unshare_remote-modal');">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    @endif

                </div>
                <p class="mb-0 text-center">※未登録ボタンは半透明テキスト</p>
            </div>
        </div>
    </div>

    <?//リモコンデザイン?>
    @include($virtual_remote->blade_path)
</div>

<!-- リモコン変更ポップアップモーダル -->
@include('modals.chg_remote_name-modal')
<!-- リモコン削除ポップアップモーダル -->
@include('modals.del_remote-modal')
<!-- リモコン共有解除ポップアップモーダル -->
@include('modals.unshare_remote-modal')

<?//広告モーダル?>   
@include('layouts.adv_popup')
    

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    //===================================================================
    //モード切り替え関数 ☆☆☆
    //===================================================================
        const DisplayArea = document.getElementById('DisplayArea');
        const EditArea = document.getElementById('EditArea');
        //const remoteNameInput = document.getElementById('remoteNameInput');
        const toggleEditModeBtn = document.getElementById('toggleEditModeBtn');
        const buttonTextSpan = document.getElementById('buttonText');
        const buttonIcon = toggleEditModeBtn.querySelector('i');

        let isEditingMode = false; // 現在のモード状態を保持

        function setEditMode(enableEdit) {
            isEditingMode = enableEdit;

            if (isEditingMode) { // 編集モードに入る
                DisplayArea.style.display = 'none';
                EditArea.style.display = 'block';

                // ボタンを「完了」に
                buttonIcon.className = 'fa-solid fa-check'; // チェックアイコン
                buttonTextSpan.textContent = '完了';
                toggleEditModeBtn.classList.remove('btn-secondary');
                toggleEditModeBtn.classList.add('btn-primary');

            } else { // 表示モードに戻る
                DisplayArea.style.display = 'block';
                EditArea.style.display = 'none';
                
                // ボタンを「設定」に
                buttonIcon.className = 'fa-solid fa-gear'; // ギアアイコン
                buttonTextSpan.textContent = '設定';
                toggleEditModeBtn.classList.remove('btn-primary');
                toggleEditModeBtn.classList.add('btn-secondary');
                
            }
        }
        // 「設定/完了」ボタンクリック
        toggleEditModeBtn.addEventListener('click', function() {
            if (isEditingMode) { // 現在が編集モード -> 「完了」が押されたと判断
                // フォームを表示モードに戻す（ページリロードされるので、これは見た目上の切り替え）
                setEditMode(false); 
                // 実際のリダイレクトはサーバー側で行われるため、ここではUIを戻すだけ
            } else { // 現在が表示モード -> 編集モードに切り替え
                setEditMode(true);
            }
        });

        // 初期状態は表示モード
        setEditMode(false);
    //===================================================================

});
</script>