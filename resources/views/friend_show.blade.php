@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="text-center ">
    <p class="card-text">フレンド：{{ $friend_profile->name }}</p>
</div>

<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills flex-nowrap">
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='mus' ? 'active' : '' }}" onclick="redirectToFriendShow('{{ $friend_profile->id }}', 'mus');">曲</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='category' ? 'active' : '' }}" onclick="redirectToFriendShow('{{ $friend_profile->id }}', 'category')">カテゴリ別</a>
        </li>
    </ul>
</div>


@if($input['table']=='mus')
    @include('layouts.list_table', ['detail_table' => $favorite_list["mus"], 'table' => 'mus'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['table' => 'mus', 'friend_id' => $friend_profile->id ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $favorite_list["mus"],'additionalParams' => $additionalParams,])

@elseif($input['table']=='category')
    <?//カテゴリ項目?>
    <div class="d-flex overflow-auto contents_box">
        <ul class="nav nav-pills">
            @foreach ($custom_category_list as $key => $category)
            <li class="nav-item nav-item-red">
                <a class="nav-link nav-link-red {{ $input['bit_num']==$category->bit_num ? 'active' : '' }}" onclick="redirectToFriendShow('{{ $friend_profile->id }}', 'category', '{{$category->bit_num}}')">
                    {{$category->name}}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <?//登録曲?>
    @include('layouts.list_table', ['detail_table' => $favorite_list["category"], 'table' => 'mus'])
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['table' => 'category' ,'bit_num' => $input['bit_num'] , 'friend_id' => $friend_profile->id ,];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('layouts.pagination', ['paginator' => $favorite_list["category"],'additionalParams' => $additionalParams,])

@endif

</div>

<div id="category" class="tab-content">

</div>

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection

<script>

    function redirectToFriendShow(friend_id, table, bit_num = null) {
        window.location.href = "{{ route('friend-show') }}?friend_id=" + friend_id + "&table=" + table + "&bit_num=" + bit_num;
    }
    
</script>

<style>
</style>
