@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="remote-header d-flex flex-column align-items-end mb-3">
            
        <?//設定ボタンを一番上（右端）に配置?>
        <button type="button" class="btn btn-secondary btn-sm mb-2" id="toggleEditModeBtn">
            <i class="fa-solid fa-gear"></i> <span id="buttonText">設定</span>
        </button>
        <div class="title-text mx-auto remote-name-display-edit-area">
            <?//表示モード?>
            <div id="DisplayArea">
                {{-- 親デバイス hub_idがなければ親デバイスとする--}}
                @if($iotdevice->hub_id == NULL)
                    <div class="device-name text-center mb-2"><h3 class="mb-0">Hub: {{ $iotdevice->name ?? '' }}</h3></div>
                    <div class="child-devices text-center mb-3">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#childDevicesCollapse{{ $iotdevice->id }}" aria-expanded="false" aria-controls="childDevicesCollapse{{ $iotdevice->id }}">
                            子デバイス ({{ $iotdevice->child_devices->count() }})
                        </button>
                        <div class="collapse mt-2" id="childDevicesCollapse{{ $iotdevice->id }}">
                            <ul class="list-unstyled mb-0">
                                @foreach($iotdevice->child_devices as $child)
                                    <li class="child-device">
                                        <small class="text-muted" style="cursor: pointer;" 
                                        onclick="window.location.href='{{ route('iotdevice-show-detail', ['id' => $child->id]) }}'">
                                            {{ $child->name ?? '' }}<i class="fa-solid fa-gear"></i>
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                {{-- 子デバイス: 初期非表示 --}}
                @else
                    <div class="device-name text-center mb-2"><h3 class="mb-0">{{ $iotdevice->name ?? '' }}</h3></div>
                    <div class="parent-device text-center mb-3">
                        <small class="text-muted" style="cursor: pointer;" 
                            onclick="window.location.href='{{ route('iotdevice-show-detail', ['id' => $iotdevice->parent_device->id]) }}'">
                            Hub: {{ $iotdevice->parent_device->name }}<i class="fa-solid fa-gear"></i>
                        </small>
                    </div>
                @endif

            </div>
            
            <?//==========================================remote_show_detail.blade.php のデザインに合わせる===================================================?>



            <div id="EditArea" style="display: none;">
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-2">
                    <?// 編集モード（最初は非表示）?>
                    <form id="iotdevicesNameChangeForm" method="POST" action="{{ route('iotdevice-chg') }}">
                        @csrf
                        <input type="hidden" name="iotdevice_id" value="{{ $iotdevice->id ?? '' }}">
                        <input type="text" class="form-control form-control-sm me-2" name="iotdevice_name" value="{{ $iotdevice->name ?? '' }}" >
                        <button type="button" class="btn btn-primary btn-sm"
                                onclick="openModal('common-modal',{
                                form_id: 'iotdevicesNameChangeForm',
                                title: 'デバイス名変更' ,mess: 'このデバイス名を変更しますか？',
                                cancel_btn: 'キャンセル',confirm_btn: '変更', user_chk: false,//チェック時にのみ実行可能
                            });">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </form>
                    <form id="iotdevicesDeleteForm" method="POST" action="{{ route('iotdevice-del') }}">
                        @csrf
                        <input type="hidden" name="iotdevice_id" value="{{ $iotdevice->id ?? '' }}">
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="openModal('common-modal',{
                                form_id: 'iotdevicesDeleteForm',
                                title: 'デバイス削除' ,mess: 'このデバイス削除しますか？',
                                cancel_btn: 'キャンセル',confirm_btn: '削除', user_chk: true,//チェック時にのみ実行可能
                            });">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>


                </div>


            
                <?//デバイスごとの処理管理==============================================================================?>
                @switch($iotdevice->type)
                    @case(0)
                        <p>赤外線リモコン</p>
                        @break

                    @case(1)
                        <span class="device-type"><i class="bi bi-lock"></i> スマートロック</span>
                        @break

                    @case(2)
                        <span class="device-type"><i class="bi bi-lightbulb"></i> 照明</span>
                        @break

                    @case(3)
                        <span class="device-type"><i class="bi bi-fan"></i> 扇風機</span>
                        @break

                    @case(4)
                        <span class="device-type"><i class="bi bi-plug"></i> コンセント</span>
                        @break

                    @case(5)
                        <span class="device-type"><i class="bi bi-thermometer-half"></i> 温度センサー</span>
                        @break

                    @case(6)
                        <span class="device-type"><i class="bi bi-wifi"></i> Wi-Fiデバイス</span>
                        @break

                    @default
                        <span class="device-type"><i class="bi bi-question-circle"></i> 未定義</span>
                @endswitch

                
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