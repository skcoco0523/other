@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
{{-- 
<a href="{{//$playlist->href }}">
    <div class="py-2 d-flex justify-content-center">
        <div class="card" style="width: 80%; height: calc(80% * 1);">
            <img src="{{ $playlist->src }}" class="card-img-top" alt="pic" style="object-fit: cover; ">
        </div>
    </div>
</a>
--}}
<div class="text-center ">
    <p class="card-text">{{ $playlist->name }}</p>
</div>

<?//メニューは別ファイルで管理 ユーザープレイリストはメニューなし?>   
@if($playlist->admin_flag == 1)
    <?//共有・お気に入り?>
    @include('layouts.icon_menu', ['detail_id' => $playlist->id, 'table' => 'pl', 'fav_flag' => $playlist->fav_flag, 'share' => 1])
    @include('layouts.list_table', ['detail_table' => $playlist->music, 'table' => 'mus'])

@else
    <?//マイプレイリスト名変更・マイプレイリスト削除?>
    @include('layouts.icon_menu', ['detail_id' => $playlist->id, 'name' => $playlist->name, 'table' => 'mypl'])
    @include('layouts.list_table', ['detail_table' => $playlist->music, 'table' => 'mus', 'pl_id' => $playlist->id])

@endif

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection