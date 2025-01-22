<?//カスタムカテゴリの更新　music_showで使用?>
@if(isset($custom_category) && isset($detail_id))
<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills">
        @foreach ($custom_category as $key => $category)
        <li class="nav-item nav-item-red">
            <a bit_num="{{ $category->bit_num }}" class="nav-link-red {{ $category->status ? 'active' : '' }}" onclick="chgToCustomCategory({{ $detail_id }}, {{ $category->bit_num }})">
                {{$category->name}}
            </a>
        </li>
        @endforeach
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // カスタムカテゴリ状態初期値を定義
        <?php if(isset($custom_category)){ ?>
            <?php foreach ($custom_category as $category)  { ?>
                setCustomCategoryActions({{$category->bit_num }}, @json($category->status));
            <?php } ?>
        <?php } ?>

    });

</script>
@endif


