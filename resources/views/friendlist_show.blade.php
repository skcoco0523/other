@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills flex-nowrap">
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='accepted' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('accepted')">フレンド</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='search' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('search')">検索</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='pending' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('pending')">承認待ち</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='request' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('request')">未承認</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='declined' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('declined')">申請拒否</a>
        </li>
    </ul>
</div>
<br>

@if($input['table']=='accepted')
    <h3>フレンド</h3>
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["accepted"], 'status' => 'accepted'])

@elseif($input['table']=="search")
    <h3>検索</h3>
    <form action="{{ route('friendlist-show') }}" method="GET">
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="search" name="friend_code" class="form-control" placeholder="ユーザーID">
            <input type="hidden" name="table" value="{{ $input['table'] }}">
        </div>
    </form>
    
    @if(count($search_user))
        @include('layouts.friend_table', ['friendlist_table' => $search_user, 'status' => $search_user[0]->status])
    @endif

@elseif($input['table']=="pending")
    <h3>承認待ち</h3> 
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["pending"], 'status' => 'pending'])

@elseif($input['table']=="request")
    <h3>未承認</h3>
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["request"], 'status' => 'request'])

@elseif($input['table']=="declined")
    <h3>申請拒否</h3>
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["declined"], 'status' => 'declined'])

@endif

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection

<script>

    function redirectToFavoriteListShow(table) {
        window.location.href = "{{ route('friendlist-show') }}?table=" + table;
    }
</script>

<style>
</style>
