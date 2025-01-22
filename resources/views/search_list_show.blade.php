@extends('layouts.app')

<?//コンテンツ?>  
@section('content')

<form action="{{ route('search-list-show') }}" method="GET">
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="search" id="searchInput" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="曲、アーティスト、アルバムなど" aria-describedby="basic-addon1">
    </div>
</form>

<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills flex-nowrap">
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='all' ? 'active' : '' }}" onclick="redirectToSearchShow('all', '{{$input['keyword'] ?? null}}');">すべて</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='art' ? 'active' : '' }}" onclick="redirectToSearchShow('art', '{{$input['keyword'] ?? null}}');">アーティスト</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='mus' ? 'active' : '' }}" onclick="redirectToSearchShow('mus', '{{$input['keyword'] ?? null}}');">曲</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='alb' ? 'active' : '' }}" onclick="redirectToSearchShow('alb', '{{$input['keyword'] ?? null}}');">アルバム</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='pl' ? 'active' : '' }}" onclick="redirectToSearchShow('pl', '{{$input['keyword'] ?? null}}');">プレイリスト</a>
        </li>
    </ul>
</div>
<br>


@if($input['table']=='all')
    <h3>アーティスト</h3>
    @include('layouts.list_table', ['non_menu_table' => $search_list["art"], 'table' => 'art'])

    <h3>曲</h3>
    @include('layouts.list_table', ['detail_table' => $search_list["mus"], 'table' => 'mus'])
    <h3>アルバム</h3>
    @include('layouts.list_table', ['detail_table' => $search_list["alb"], 'table' => 'alb'])
    <h3>プレイリスト</h3>
    @include('layouts.list_table', ['detail_table' => $search_list["pl"], 'table' => 'pl'])

@elseif($input['table']=="art")
    <h3>アーティスト</h3>
    @include('layouts.list_table', ['non_menu_table' => $search_list["art"], 'table' => 'art'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['keyword' => $input['keyword'] ?? '' ,'table' => 'art' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $search_list["art"],'additionalParams' => $additionalParams,])

@elseif($input['table']=="mus")
    <h3>曲</h3>
    @include('layouts.list_table', ['detail_table' => $search_list["mus"], 'table' => 'mus'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['keyword' => $input['keyword'] ?? '' ,'table' => 'mus' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $search_list["mus"],'additionalParams' => $additionalParams,])

@elseif($input['table']=="alb")
    <h3>アルバム</h3> 
    @include('layouts.list_table', ['detail_table' => $search_list["alb"], 'table' => 'alb'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['keyword' => $input['keyword'] ?? '' ,'table' => 'alb' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $search_list["alb"],'additionalParams' => $additionalParams,])

@elseif($input['table']=="pl")
    <h3>プレイリスト</h3>
    @include('layouts.list_table', ['detail_table' => $search_list["pl"], 'table' => 'pl'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['keyword' => $input['keyword'] ?? '' ,'table' => 'pl' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $search_list["pl"],'additionalParams' => $additionalParams,])


@endif

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection

<script>
    // 初期表示設定
    document.addEventListener('DOMContentLoaded', function() {
        //document.getElementById('all').style.display = 'block';

        // 検索フォームにフォーカスが当たったらリダイレクトする
        const searchInput = document.getElementById('searchInput');
        
        searchInput.addEventListener('focus', function() {
            const keyword = searchInput.value.trim(); // 検索フォームの値を取得
            const url = "{{ route('search-show') }}" + "?keyword=" + encodeURIComponent(keyword);
            window.location.href = url;
        });

    });
    
    function redirectToSearchShow(table, keyword = null) {
        window.location.href = "{{ route('search-list-show') }}?table=" + table + "&keyword=" + keyword;
    }
</script>