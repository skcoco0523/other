@extends('layouts.app')

@section('content')

@php
    $tab_name = [
        'accepted' => 'フレンド',
        'search'   => '検索',
        'pending'  => '承認待ち',
        'request'  => '未承認',
        'declined' => '申請拒否',
    ];
    if($input['table']=="search"){
        $status = $friendlist['search'][0]->status ?? '';
    }else{
        $status = $input['table'];
    }
@endphp

<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills flex-nowrap">
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='accepted' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('accepted')">{{$tab_name['accepted']}}</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='search' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('search')">{{$tab_name['search']}}</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='pending' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('pending')">{{$tab_name['pending']}}</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='request' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('request')">{{$tab_name['request']}}</a>
        </li>
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $input['table']=='declined' ? 'active' : '' }}" onclick="redirectToFavoriteListShow('declined')">{{$tab_name['declined']}}</a>
        </li>
    </ul>
</div>
<br>

<h3>{{$tab_name[$input['table']]}}</h3>
@if($input['table']=="search")
    <form action="{{ route('friendlist-show') }}" method="GET">
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="search" name="friend_code" class="form-control" placeholder="ユーザーID">
            <input type="hidden" name="table" value="{{ $input['table'] }}">
        </div>
    </form>

@endif
<table class="table table-borderless table-center">
    <tbody>

        @foreach ($friendlist[$input['table']] as $key => $friend)   
            <tr>
            @if ($status == 'accepted')
                <td class="col-8" onclick="redirectToFriendShow({{ $friend->id }})">
            @else
                <td class="col-8">
            @endif
                    {{Str::limit($friend->name, 30, '...')}}
                </td>
                <td class="col-4">
                    @if ($status == 'pending')
                        <a class="btn btn-gray" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'cancel')">キャンセル</a>
                        
                    @elseif ($status == 'request')
                        <div class="button-group">
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-accept') }}', 'accept')">承諾</a>
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-decline') }}', 'decline')">拒否</a>
                        </div>
                    @elseif ($status == 'declined')
                        <div class="button-group">
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-accept') }}', 'accept')">承諾</a>
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'del')">削除</a>
                        </div>
                    @elseif ($status == 'accepted')
                        <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'del')">削除</a>
                    @else
                        <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-request') }}', 'request')">申請</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection


{{--申請・承認・削除用フォーム--}}
<form name="friend_reqest_form" method="" action="">
    @csrf
    <input type="hidden" name="user_id" value="">
</form>

<script>

    function redirectToFavoriteListShow(table) {
        window.location.href = "{{ route('friendlist-show') }}?table=" + table;
    }
    function redirectToFriendShow(friend_id) {
        window.location.href = "{{ route('friend-show') }}?friend_id=" + friend_id;
    }

    function friend_reqest(friend_id,route,method){
        if (method === 'del') {
            var rtn = confirm('フレンドから削除してもよろしいですか？');
            if (rtn === false) return false;
        }
        var trg = document.forms["friend_reqest_form"];
        trg.method="post";
        trg.action = route; // 第2引数のrouteをactionに指定
        trg["user_id"].value = friend_id;

        trg.submit();
    }

</script>

<style>
</style>
