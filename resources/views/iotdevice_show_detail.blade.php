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
                <h3 class="mb-0 text-center">{{ $iotdevice->name ?? '' }}</h3>
            </div>
            <div id="EditArea" style="display: none;">
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-2">
                    <?// 編集モード（最初は非表示）?>
                    <form id="iotdevicesNameChangeForm" method="POST" action="{{ route('iotdevice-chg') }}" class="d-inline-flex align-items-center">
                        @csrf
                        <input type="hidden" name="iotdevice_id" value="{{ $iotdevice->id ?? '' }}">
                        <input type="text" class="form-control form-control-sm me-2" name="iotdevice_name" value="{{ $iotdevice->name ?? '' }}" >
                        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('common-modal',chg_params);">
                                <i class="fa-solid fa-pen"></i>
                        </button>
                    </form>
                        <form id="iotdevicesDeleteForm" method="POST" action="{{ route('remote-del') }}" class="d-inline-flex align-items-center">
                            @csrf
                            <input type="hidden" name="iotdevice_id" value="{{ $iotdevice->id ?? '' }}">
                            <button type="button" class="btn btn-danger btn-sm" onclick="openModal('common-modal',del_params);">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                </div>
            </div>
            <p class="detail-txt mb-0 text-center">
                所有者：{{ $iotdevice->uname }}
            </p>
        </div>
    </div> 
</div>


<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection


<script>
    const chg_params = {
        form_id: "iotdevicesNameChangeForm",
        title: "デバイス名変更",mess: "このデバイス名を変更しますか？",
        cancel_btn: "キャンセル",confirm_btn: "変更",
    }
    const del_params = {
        form_id: "iotdevicesDeleteForm",
        title: "デバイス名削除",mess: "このデバイス削除しますか？",
        cancel_btn: "キャンセル",confirm_btn: "削除",
    }
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