@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="py-2 d-flex justify-content-center">
    <p class="card-text">{{ $category_ranking->name }}</p>

</div>
<?//テーブルリストは別ファイルで管理?>   
@include('layouts.list_table', ['detail_table' => $category_ranking, 'table' => 'mus'])

{{--ﾊﾟﾗﾒｰﾀ--}}
@php
    $additionalParams = [
        'table' => 'mus', 'id' => $id ?? '',
    ];
@endphp
{{--ﾍﾟｰｼﾞｬｰ--}}
@include('layouts.pagination', ['paginator' => $category_ranking,'additionalParams' => $additionalParams,])

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection


<script>
</script>