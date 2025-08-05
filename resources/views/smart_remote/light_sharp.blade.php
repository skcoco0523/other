<?//button type="submitにしないと、プレビューで処理されてしまう"?>
<div class="remote-body">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">

            <div class="row g-3 mt-3">
                <div class="col-6">
                    <button type="button" class="remote-button remote-button-h50" style="background-color: #a9aaabff;" button-num="1">
                        <div class="remote-button-text remote-button-str2 @if(!isset($r_sig[1])) noset-signal @endif">
                            消灯
                        </div>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="remote-button remote-button-h50" style="background-color: #fd7e14;" button-num="2">
                        <div class="remote-button-text remote-button-str2 @if(!isset($r_sig[2])) noset-signal @endif">
                            全灯
                        </div>
                    </button>
                </div>
            </div>

            <div class="row g-3 mt-0">
                <div class="col-6">
                </div>
                <div class="col-6">
                    <button type="button" class="remote-button remote-button-h50" style="background-color: #ffc107;" button-num="3">
                        <div class="remote-button-text remote-button-str2 @if(!isset($r_sig[3])) noset-signal @endif">
                            常夜灯
                        </div>
                    </button>
                </div>
            </div>


            {{-- ダイヤルデザイン --}}
            <div class="remote-dial-container mt-3" style="background-color: #f0f0f0;" >
                {{-- 左のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-left" style="background-color: #f0f0f0;" button-num="4">         
                    <span class="remote-dial-text remote-button-str1 @if(!isset($r_sig[4])) noset-signal @endif">
                        寒<br>色
                    </span>
                    <i class="fa-solid fa-caret-left @if(!isset($r_sig[4])) noset-signal @endif"></i>
                </button>
                {{-- 右のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-right" style="background-color: #f0f0f0;" button-num="5">
                    <i class="fa-solid fa-caret-right @if(!isset($r_sig[5])) noset-signal @endif"></i>
                    <span class="remote-dial-text remote-button-str1 @if(!isset($r_sig[5])) noset-signal @endif">
                        暖<br>色
                    </span>
                </button>
                {{-- 上のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-top" style="background-color: #f0f0f0;" button-num="6">
                    <span class="remote-dial-text remote-button-str1 @if(!isset($r_sig[6])) noset-signal @endif">
                        明
                    </span>
                    <i class="fa-solid fa-caret-up @if(!isset($r_sig[6])) noset-signal @endif"></i>
                </button>
                {{-- 下のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-bottom" style="background-color: #f0f0f0;" button-num="7">
                    <i class="fa-solid fa-caret-down @if(!isset($r_sig[7])) noset-signal @endif"></i>
                    <span class="remote-dial-text remote-button-str1 @if(!isset($r_sig[7])) noset-signal @endif">
                        暗
                    </span>
                </button>

                {{-- 中央のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-center" style="background-color: #fd7e14;" button-num="8">
                    <span class="remote-button-text remote-button-str2 @if(!isset($r_sig[8])) noset-signal @endif">
                        点灯
                    </span>
                </button>
            </div>


            <div class="row g-3 mt-3">
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1 @if(!isset($r_sig[9])) noset-signal @endif">
                            エコ調光
                        </div>
                        <button type="button" class="remote-button remote-button-h20" style="background-color: #f0f0f0;" button-num="9"></button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1 @if(!isset($r_sig[10])) noset-signal @endif">
                            おやすみリズム
                        </div>
                        <button type="button" class="remote-button remote-button-h20" style="background-color: #f0f0f0;" button-num="10"></button>
                    </div>
                </div>
            </div>

            
            <div class="row g-3 mt-1">
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1 @if(!isset($r_sig[11])) noset-signal @endif">
                            留守モード
                        </div>
                        <button type="button" class="remote-button remote-button-h20" style="background-color: #f0f0f0;" button-num="11"></button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1 @if(!isset($r_sig[12])) noset-signal @endif">
                            切タイマー
                        </div>
                        <button type="button" class="remote-button remote-button-h20" style="background-color: #f0f0f0;" button-num="12"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

@if (!isset($preview) || !$preview)
    <script>
        
    </script>

@endif