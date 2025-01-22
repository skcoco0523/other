@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<a href="{{$album->href }}">
    <img src="{{ $album->src }}" class="icon-80p" alt="pic">
</a>
<div class="text-center">
    <p class="detail-title">{{ $album->name }}</p>
    <p class="detail-txt">
        <a class="no-decoration" href="{{ route('artist-show',  ['id' => $album->art_id]) }}">
            アーティスト：{{ $album->art_name }}
        </a>
    </p>   
    <p>{{ $album->release_date }}：{{$album->mus_cnt }}曲</p>
</div>

<?//メニューは別ファイルで管理  シェア、お気に入り?>   
@include('layouts.icon_menu', ['detail_id' => $album->id, 'table' => 'alb', 'fav_flag' => $album->fav_flag, 'share' => 1])

<?//テーブルリストは別ファイルで管理?>
@include('layouts.list_table', ['detail_table' => $album->music, 'table' => 'mus'])

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection

