<div class="container-fluid">
    <nav class="nav nav-pills nav-justified">
        <?//music playlist artist　　共有　お気に入り?>

        <?//シェア?>
        @if(isset($detail_id) && isset($table) && isset($share))
            <a class="nav-link p-2 d-flex flex-column align-items-center">
                <i class="fa-regular fa-share-from-square icon-20 red" onclick="openModal('share-modal', null, '{{ request()->url()}}?id={{ $detail_id }}')"></i>
            </a>
            @include('modals.share-modal')
        @endif

        <?//お気に入り?>
        @if(isset($detail_id) && isset($table) && isset($fav_flag))
            <a class=" nav-link p-2 d-flex flex-column align-items-center">
                @if($fav_flag)
                    <i data-favorite-id="{{ $table }}-{{ $detail_id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail_id }})"></i>
                @else
                    <i data-favorite-id="{{ $table }}-{{ $detail_id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail_id }})"></i>
                @endif
            </a>
        @endif

        <?//プレイリスト追加?>
        @if(isset($detail_id) && isset($table) && $table == "mus")
            <a class="nav-link p-2 d-flex flex-column align-items-center">
                <i class="fa-regular fa-square-plus icon-20 red" onclick="openModal('add_pl_modal', {{$detail_id}}, '{{request()->fullUrl()}}')"></i>
            </a>
            @include('modals.add_playlist-modal')
        @endif

        <?//プレイリスト変更,削除?>
        @if(isset($detail_id) && isset($table) && $table == "mypl")
            <a class="nav-link p-2 d-flex flex-column align-items-center">
                <i class="fa-regular fa-pen-to-square icon-20 red" onclick="openModal('edit_pl_modal')"></i>
            </a>
            @include('modals.edit_playlist-modal', ['detail_id' => $detail_id,])

            <a class="nav-link p-2 d-flex flex-column align-items-center">
                <i class="fa-regular fa-trash-can icon-20 red" onclick="openModal('del_pl_modal')"></i>
            </a>
            @include('modals.del_playlist-modal', ['detail_id' => $detail_id,])
        @endif
            
    </nav>
</div>
 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // お気に入り状態初期値を定義
        <?php if(isset($detail_id) && isset($table) && isset($fav_flag)){ ?>
            setFavoriteActions('{{$table}}',{{ $detail_id }}, {{$fav_flag}});
        <?php } ?>
    });

</script>