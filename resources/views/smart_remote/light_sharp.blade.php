<?//button type="submitにしないと、プレビューで処理されてしまう"?>
<div class="remote-body">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">

            <div class="row g-3 mt-3">
                <div class="col-6">
                    <button type="button" class="remote-button remote-button-h50" style="background-color: #a9aaabff;" button-num="1">
                        <div class="remote-button-text remote-button-str2">消灯</div>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="remote-button remote-button-h50" style="background-color: #fd7e14;" button-num="2">
                        <div class="remote-button-text remote-button-str2">全灯</div>
                    </button>
                </div>
            </div>

            <div class="row g-3 mt-0">
                <div class="col-6">
                </div>
                <div class="col-6">
                    <button type="button" class="remote-button remote-button-h50" style="background-color: #ffc107;" button-num="3">
                        <div class="remote-button-text remote-button-str2">常夜灯</div>
                    </button>
                </div>
            </div>


            {{-- ダイヤルデザイン --}}
            <div class="remote-dial-container mt-3" style="background-color: #f0f0f0;" >
                {{-- 左のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-left" style="background-color: #f0f0f0;" button-num="4">         
                    <span class="remote-dial-text remote-button-str1">寒<br>色</span>
                    <i class="fa-solid fa-caret-left"></i>
                </button>
                {{-- 右のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-right" style="background-color: #f0f0f0;" button-num="5">
                    <i class="fa-solid fa-caret-right"></i>
                    <span class="remote-dial-text remote-button-str1">暖<br>色</span>
                </button>
                {{-- 上のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-top" style="background-color: #f0f0f0;" button-num="6">
                    <span class="remote-dial-text remote-button-str1">明</span>
                    <i class="fa-solid fa-caret-up"></i>
                </button>
                {{-- 下のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-bottom" style="background-color: #f0f0f0;" button-num="7">
                    <i class="fa-solid fa-caret-down"></i>
                    <span class="remote-dial-text remote-button-str1">暗</span>
                </button>

                {{-- 中央のボタン --}}
                <button type="button" class="remote-dial-button remote-dial-button-center" style="background-color: #fd7e14;" button-num="8">
                    <span class="remote-button-text remote-button-str2">点灯</span>
                </button>
            </div>


            <div class="row g-3 mt-3">
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1">エコ調光</div>
                        <button type="button" class="remote-button remote-button-h20" style="background-color: #f0f0f0;" button-num="9"></button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1">おやすみリズム</div>
                        <button type="button" class="remote-button remote-button-h20" style="background-color: #f0f0f0;" button-num="10"></button>
                    </div>
                </div>
            </div>

            
            <div class="row g-3 mt-1">
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1">留守モード</div>
                        <button type="button" class="remote-button remote-button-h20" style="background-color: #f0f0f0;" button-num="11"></button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="remote-label-text remote-button-str1">切タイマー</div>
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