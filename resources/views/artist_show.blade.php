@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="text-center ">
    <p class="card-text">{{ $artist->art_name }}</p>
    @if($artist->debut)
        <p>デビュー：{{ \Carbon\Carbon::parse($artist->debut)->format('Y-m') }}</p>
    @endif
</div>

<?//メニューは別ファイルで管理  シェア?>   
@include('layouts.icon_menu', ['detail_id' => $artist->id, 'table' => 'art', 'share' => 1])

<?//テーブルリストは別ファイルで管理?>
<h3>アルバム</h3>
@include('layouts.list_table', ['detail_table' => $album, 'table' => 'alb'])


<h3>曲</h3>
@include('layouts.list_table', ['detail_table' => $music, 'table' => 'mus'])
{{--ﾊﾟﾗﾒｰﾀ--}}
@php
    $additionalParams = ['id' => $artist->id,];
@endphp
{{--ﾍﾟｰｼﾞｬｰ--}}
@include('layouts.pagination', ['paginator' => $music,'additionalParams' => $additionalParams,])

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection

<script>
    function redirectToMusicShow(id) {
        window.location.href = "{{ route('music-show') }}?id=" + id;
    }
</script>