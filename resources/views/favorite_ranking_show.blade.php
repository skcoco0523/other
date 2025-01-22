@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="py-2 d-flex justify-content-center">
    <p class="card-text">{{ $fav_ranking->name }}</p>

</div>
<?//テーブルリストは別ファイルで管理?>   
@include('layouts.list_table', ['detail_table' => $fav_ranking, 'table' => $fav_ranking->table])

{{--ﾊﾟﾗﾒｰﾀ--}}
@php
    $additionalParams = [
        'table' => $fav_ranking->table ?? '',
    ];
@endphp
{{--ﾍﾟｰｼﾞｬｰ--}}
@include('layouts.pagination', ['paginator' => $fav_ranking,'additionalParams' => $additionalParams,])

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection


<script>
</script>