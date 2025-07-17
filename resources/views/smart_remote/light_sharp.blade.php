<?//button type="submitにしないと、プレビューで処理されてしまう"?>
<div class="remote-body">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">
            {{-- remote-row を追加し、その中にボタンを配置 --}}
            <div class="remote-row mt-3">
                <button type="button" class="remote-button" style="background-color: #6c757d;" data-button-num="1">
                    <span>消灯</span>
                </button>
                <button type="button" class="remote-button" style="background-color: #fd7e14;" data-button-num="2">
                    <span>全灯</span>
                </button>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="remote-row">
                <div class="remote-button button-transparent"></div>
                <button type="button" class="remote-button" style="background-color: #ffc107;" data-button-num="3">
                    <span>常夜灯</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ダイヤルデザイン --}}
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="remote-dial-row mt-3">
                <div class="remote-dial-container" style="background-color: #f0f0f0;" >
                    {{-- 左のボタン --}}
                    <button type="button" class="dial-button dial-button-left" style="background-color: #f0f0f0;" data-button-num="4">
                        <i class="fa-solid fa-caret-left"></i>
                        <span>寒色</span>
                    </button>
                    {{-- 右のボタン --}}
                    <button type="button" class="dial-button dial-button-right" style="background-color: #f0f0f0;" data-button-num="5">
                        <i class="fa-solid fa-caret-right"></i>
                        <span>暖色</span>
                    </button>
                    {{-- 上のボタン --}}
                    <button type="button" class="dial-button dial-button-top" style="background-color: #f0f0f0;" data-button-num="6">
                        <i class="fa-solid fa-caret-up"></i>
                        <span>明</span>
                    </button>
                    {{-- 下のボタン --}}
                    <button type="button" class="dial-button dial-button-bottom" style="background-color: #f0f0f0;" data-button-num="7">
                        <i class="fa-solid fa-caret-down"></i>
                        <span>暗</span>
                    </button>

                    {{-- 中央のボタン --}}
                    <button type="button" class="dial-button dial-button-center" style="background-color: #fd7e14;" data-button-num="8">
                        <span>点灯</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="remote-row mt-3">
                <button type="button" class="remote-button" style="background-color: #f0f0f0;" data-button-num="9">
                    <span>エコ調光</span>
                </button>
                <button type="button" class="remote-button" style="background-color: #f0f0f0;" data-button-num="10">
                    <span>おやすみリズム</span>
                </button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="remote-row">
                <button type="button" class="remote-button" style="background-color: #f0f0f0;" data-button-num="11">
                    <span>留守モード</span>
                </button>
                <button type="button" class="remote-button" style="background-color: #f0f0f0;" data-button-num="12">
                    <span>切タイマー</span>
                </button>
            </div>
        </div>
    </div>
    
</div>

@if (!isset($preview) || !$preview)
    <script>
        
    </script>
@endif