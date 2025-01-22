@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a class="no-decoration" href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills flex-nowrap">
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='all' ? 'active' : '' }}" onclick="redirectToFavoriteShow('all');">すべて</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='mypl' ? 'active' : '' }}" onclick="redirectToFavoriteShow('mypl');">Myプレイリスト</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='category' ? 'active' : '' }}" onclick="redirectToFavoriteShow('category');">カテゴリ別</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='mus' ? 'active' : '' }}" onclick="redirectToFavoriteShow('mus');">曲</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='alb' ? 'active' : '' }}" onclick="redirectToFavoriteShow('alb');">アルバム</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='pl' ? 'active' : '' }}" onclick="redirectToFavoriteShow('pl');">プレイリスト</a>
        </li>
    </ul>
</div>
<br>

{{--すべて--}}
@if($input['table']=='all')
    <h3>曲</h3>
    @include('layouts.list_table', ['detail_table' => $favorite_list["mus"], 'table' => 'mus'])
    <h3>アルバム</h3>
    @include('layouts.list_table', ['detail_table' => $favorite_list["alb"], 'table' => 'alb'])
    <h3>プレイリスト</h3>
    @include('layouts.list_table', ['detail_table' => $favorite_list["pl"], 'table' => 'pl'])

{{--Myプレイリスト--}}
@elseif($input['table']=="mypl")
    <h3>Myプレイリスト</h3>

    <table class="table table-borderless table-center">
        <td class="col-2 icon-55 d-flex justify-content-center b-gray" onclick="openModal('create_pl_modal');">
            <i class="fa-solid fa-plus icon-25 red"></i>
        </td>
        <td class="col-10" onclick="openModal('create_pl_modal');">
            新規マイプレイリスト
        </td>
    </table>
    <!-- プレイリスト追加ポップアップモーダル -->
    @include('modals.create_playlist-modal')


    @include('layouts.list_table', ['non_menu_table' => $favorite_list["mypl"], 'table' => 'mypl'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['table' => 'mypl' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $favorite_list["mypl"],'additionalParams' => $additionalParams,])


{{--カテゴリ別--}}
@elseif($input['table']=="category")
    <h3>カテゴリ別</h3>
    <?//カテゴリ項目?>
    <div class="d-flex overflow-auto contents_box">
        <ul class="nav nav-pills">
            @foreach ($custom_category_list as $key => $category)
            <li class="nav-item nav-item-red">
                <a class="nav-link nav-link-red {{ $input['bit_num']==$category->bit_num ? 'active' : '' }}" onclick="redirectToFavoriteShow('category','{{$category->bit_num}}')">
                    {{$category->name}}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <?//登録曲?>
    @include('layouts.list_table', ['non_menu_table' => $favorite_list["category"], 'table' => 'mus'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['table' => 'category' ,'bit_num' => $input['bit_num'] ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $favorite_list["category"],'additionalParams' => $additionalParams,])

{{--曲--}}
@elseif($input['table']=="mus")
    <h3>曲</h3>
    @include('layouts.list_table', ['detail_table' => $favorite_list["mus"], 'table' => 'mus'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['table' => 'mus' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $favorite_list["mus"],'additionalParams' => $additionalParams,])

{{--アルバム--}}
@elseif($input['table']=="alb")
    <h3>アルバム</h3>
    @include('layouts.list_table', ['detail_table' => $favorite_list["alb"], 'table' => 'alb'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['table' => 'alb' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $favorite_list["alb"],'additionalParams' => $additionalParams,])

{{--プレイリスト--}}
@elseif($input['table']=="pl")
    <h3>プレイリスト</h3>
    @include('layouts.list_table', ['detail_table' => $favorite_list["pl"], 'table' => 'pl'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['table' => 'pl' ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $favorite_list["pl"],'additionalParams' => $additionalParams,])

@endif

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection

<script>
    function redirectToFavoriteShow(table, bit_num = null) {
        window.location.href = "{{ route('favorite-show') }}?table=" + table + "&bit_num=" + bit_num;
    }
</script>

<style>
</style>
