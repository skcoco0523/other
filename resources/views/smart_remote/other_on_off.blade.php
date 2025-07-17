<?//button type="submitにしないと、プレビューで処理されてしまう"?>
<div class="remote-body">
    <div class="remote-row power-section">
        <button type="button" class="remote-button button-info button-square" data-button-num="1" style="color: orange;">
            <span>オン</span>
        </button>
        <button type="button" class="remote-button button-info button-square" data-button-num="2" style="color: lightblue;">
            <span>オフ</span>
        </button>
    </div>
</div>

@if (!isset($preview) || !$preview)
    <script>
        
    </script>
@endif