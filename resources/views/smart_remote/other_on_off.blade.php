
<div class="remote-body other-remote-body"> {{-- other-remote-body は固有スタイル用 --}}
    <div class="remote-row power-section">
        <button class="remote-button button-info button-square" data-button-num="1" style="color: orange;">
            <span>オン</span>
        </button>
        <button class="remote-button button-info button-square" data-button-num="2" style="color: lightblue;">
            <span>オフ</span>
        </button>
    </div>
</div>

@if (!isset($preview) || !$preview)
    <script>
        
    </script>
@endif