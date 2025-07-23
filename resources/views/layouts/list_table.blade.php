<?//テーブル用モーダル?>
@if(isset($table))
    <?//マイプレイリストへの追加?>
    @if($table == "mus" && !(isset($pl_id)))
        @include('modals.add_playlist-modal')
    @endif

    <?//マイプレイリストからの削除?>
    @if($table == "mus" && isset($pl_id))
        @include('modals.out_playlist-modal', ['pl_id' => $pl_id,])
    @endif

@endif

@if(isset($recommend_table))
    <?//マイプレイリストへの追加?>
    @if($recommend_table->table == "mus")
        @include('modals.add_playlist-modal')
    @endif

@endif


                        <table class="table table-borderless table-data-center" style="table-layout: fixed;">
                            <tbody>

<?//メニューなしテーブル?>
@if(isset($non_menu_table) && isset($table))
                            <colgroup>
                                <col style="width: 20%; min-width: 70px;">
                                <col style="width: 80%">
                            </colgroup>
    @foreach ($non_menu_table as $key => $detail)
                                    <tr class="table-row">
                                        <td onclick="redirectToDetailShow({{ $detail->id }},'{{ $table }}')">
                                            {{--画像参照を切り替える--}}
        @if(isset($detail->src)) 
                                            <img src="{{ $detail->src }}" class="icon-55">
        @else
                                            <img src="{{ asset('img/pic/no_image.png') }}" class="icon-55">
        @endif
                                        </td>
                                        <td onclick="redirectToDetailShow({{ $detail->id }},'{{ $table }}')">
                                            {{ $detail->name }}
                                        </td>
                                    </tr>
    @endforeach
@endif

<?//?>
@if(isset($virtual_remote_table) && isset($table))
                            <colgroup>
                                <col style="width: 20%; min-width: 70px;">
                                <col style="width: 80%">
                            </colgroup>
    @foreach ($virtual_remote_table as $key => $detail)
                                    <tr class="table-row">
                                        <td class="col-2 icon-55 d-flex justify-content-center" onclick="redirectToDetailShow({{ $detail->id }},'{{ $table }}')">
                                            {{--画像参照を切り替える--}}
        @if(isset($detail->icon_class))
                                            <i class="fa-solid {{ $detail->icon_class }} red icon-25"></i>
        @else
                                            <img src="{{ asset('img/pic/no_image.png') }}" class="icon-55">
        @endif

                                        </td>
                                        <td onclick="redirectToDetailShow({{ $detail->id }},'{{ $table }}')">
                                            {{ $detail->name }}
                                        </td>
                                    </tr>
    @endforeach
@endif

<?//?>
@if(isset($iot_devices_table) && isset($table))
                            <colgroup>
                                <col style="width: 20%; min-width: 70px;">
                                <col style="width: 80%">
                            </colgroup>
    @foreach ($iot_devices_table as $key => $detail)
                                    <tr class="table-row">
                                        <td class="col-2 icon-55 d-flex justify-content-center" onclick="redirectToDetailShow({{ $detail->id }},'{{ $table }}')">
                                            {{--画像参照を切り替える--}}

        @if(isset($detail->icon_class))
                                            <i class="fa-solid {{ $detail->icon_class }} red icon-25"></i>
        @else
                                            <img src="{{ asset('img/pic/no_image.png') }}" class="icon-55">
        @endif
                                        </td>
                                        <td onclick="redirectToDetailShow({{ $detail->id }},'{{ $table }}')">
                                            {{ $detail->name }}
                                        </td>
                                    </tr>
    @endforeach
@endif


<?//メニューありテーブル　曲　アルバム　プレイリスト?>
@if(isset($detail_table) && isset($table))
                            <colgroup>
                                <col style="width: 20%; min-width: 70px;">
                                <col style="width: 60%">
                                <col style="width: 10%">
                                <col style="width: 10%">
                            </colgroup>
    @foreach ($detail_table as $key => $detail)         
                                <tr>
                                    <td onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
        @if(isset($detail->src))        <img src="{{ $detail->src }}" class="icon-55">
        @elseif(isset($detail->detail[0]->src))         
                                        <img src="{{ $detail->detail[0]->src }}" class="icon-55">
        @elseif(isset($detail->detail[0]->music[0]->src))
                                        <img src="{{ $detail->detail[0]->music[0]->src }}" class="icon-55">
        @elseif(isset($detail->music[0]->src))         
                                        <img src="{{ $detail->music[0]->src }}" class="icon-55">
        @elseif($table == "art")
                                        <img src="{{ asset('img/pic/artist.png') }}" class="icon-55">
        @else
                                        <img src="{{ asset('img/pic/no_image.png') }}" class="icon-55">
        @endif
                                    </th>
                                    
                                    <td onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
                                        <p class="table-title">{{ $detail->name }}</p>
        @if(isset($detail->art_name))   <p class="table-sub-title">{{ $detail->art_name }}</p>
        @endif
                                    </td>
                                    <td>
        @if($detail->fav_flag)          <i data-favorite-id="{{ $table }}-{{ $detail->id }}" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail->id }})"></i>
        @else                           <i data-favorite-id="{{ $table }}-{{ $detail->id }}" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite('{{ $table }}',{{ $detail->id }})"></i>
        @endif
                                    </td>
        <?//マイプレイリストへの曲追加?>
        @if($table == "mus" && !(isset($pl_id)))
                                    <td>
                                        <i class="fa-regular fa-square-plus icon-20 red" onclick="openModal('add_pl_modal', {{$detail->id}}, '{{request()->fullUrl()}}')"></i>
                                    </td>
        @endif
        <?//マイプレイリストから曲削除?>
        @if($table == "mus" && isset($pl_id))
                                    <td>
                                        <i class="fa-regular fa-trash-can icon-20 red" onclick="openModal('out_pl_modal', {{$detail->id}}, '{{request()->fullUrl()}}')"></i>
                                    </td>
        @endif
                                </tr>
    @endforeach
@endif




<?//フレンド情報表示用?>
@if(isset($friend_table) && isset($table))
                            <colgroup>
                                <col style="width: 20%; min-width: 70px;">
                                <col style="width: 80%">
                            </colgroup>
    @foreach ($friend_table as $key => $detail)   
                                <tr class="table-row">
                                    <td onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
    @if(isset($detail->src))            <img src="{{ $detail->src }}" class="icon-55">
    @else                               <p style="margin: 0 auto; text-align: center;">{{ $key + 1 }}</p>
    @endif
                                    </th>
                                    <td onclick="redirectToDetailShow({{$detail->id}},'{{ $table }}')">
                                        <p class="table-title">{{ $detail->name }}</p>
    @if(isset($detail->art_name))       <p class="table-sub-title">{{ $detail->art_name }}</p>
    @endif
                                    </td>
                                </tr>
    @endforeach
@endif


                            </tbody>
                        </table>




<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        // お気に入り状態初期値を定義

        <?php if(isset($detail_table)){ ?>
            <?php foreach ($detail_table as $detail): ?>
                setFavoriteActions('{{$table}}',{{ $detail->id }}, {{$detail->fav_flag}});
            <?php endforeach; ?>
        <?php } ?>

    });

    function redirectToDetailShow(detail_id,table) {
        switch(table){
            case "iotdevice":
                window.location.href = "{{ route('iotdevice-show-detail') }}?id=" + detail_id;
                break;
            case "virtual_remote":
                window.location.href = "{{ route('remote-show-detail') }}?id=" + detail_id;
                break;
            default:
                break;
        }
    }

</script>
