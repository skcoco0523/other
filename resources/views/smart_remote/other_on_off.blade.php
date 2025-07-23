<?//button type="submitにしないと、プレビューで処理されてしまう"?>
<div class="remote-body">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-12">

            <div class="row remote-row g-2 mt-3">
                <div class="col-6">
                    <button type="button" class="remote-button" data-button-num="1" style="color: orange;" data-button-num="1">
                        <span>オン</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="remote-button" data-button-num="2" style="color: lightblue;" data-button-num="2">
                        <span>オフ</span>
                    </button>
                </div>
            </div>


            
        </div>

    </div>
</div>

@if (!isset($preview) || !$preview)
    <script>
        
    </script>
@endif