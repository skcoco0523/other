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
                <h3 id="DisplayArea" class="mb-0 text-center">{{ $virtual_remote->name ?? 'リモコン' }}</h3>
                
                <?// 編集モード（最初は非表示）?>
                <div id="EditArea" style="display: none;">
                    <form id="remoteNameChangeForm" method="POST" action="{{ route('remote-change') }}" class="text-center">
                        @csrf
                        <input type="hidden" name="id" value="{{ $virtual_remote->remote_id ?? '' }}">
                        <input type="hidden" name="search_remote_id" value="{{ $virtual_remote->id ?? '' }}">
                        <input type="hidden" name="user_admin_flag" value="{{ $virtual_remote->admin_flag ?? '' }}">
                        <input type="text" class="form-control form-control-sm d-inline-block w-auto" id="remoteNameInput" name="remote_name" value="{{ $virtual_remote->name ?? '' }}" required>
                        <button type="submit" class="btn btn-primary btn-sm ms-2" id="submitRemoteNameBtn"> 変更</button>
                    </form>

                    <p class="mb-0 text-center">※未登録ボタンは半透明テキスト</p>
                    
                </div>
            </div>
        </div>

        <?//リモコンデザイン?>
        @include($virtual_remote->blade_path)
    </div>

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

        const remoteBodyContainer = document.querySelector('.remote-body'); // リモコンのボタンを囲む親要素

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

                //編集モードにするためのクラスを追加
                if (remoteBodyContainer) {
                    remoteBodyContainer.classList.add('remote-edit-mode');
                }

            } else { // 表示モードに戻る

                DisplayArea.style.display = 'block';
                EditArea.style.display = 'none';
                
                // ボタンを「設定」に
                buttonIcon.className = 'fa-solid fa-gear'; // ギアアイコン
                buttonTextSpan.textContent = '設定';
                toggleEditModeBtn.classList.remove('btn-primary');
                toggleEditModeBtn.classList.add('btn-secondary');

                if (remoteBodyContainer) {
                    remoteBodyContainer.classList.remove('remote-edit-mode');
                }
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