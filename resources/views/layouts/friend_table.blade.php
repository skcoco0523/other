
<table class="table table-borderless table-center">
    <tbody>

        @if(isset($friendlist_table) && isset($status))
            @foreach ($friendlist_table as $key => $friend)   
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
                            <a class="btn btn-gray" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'cancel')">
                                キャンセル</a>
                            
                        @elseif ($status == 'request')
                        <div class="button-group">
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-accept') }}', 'accept')">
                                承諾</a>
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-decline') }}', 'decline')">
                                拒否</a>
                            </div>
                        @elseif ($status == 'declined')
                        <div class="button-group">
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-accept') }}', 'accept')">
                                承諾</a>
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'del')">
                                削除</a>
                            </div>
                        @elseif ($status == 'accepted')
                            <a class="btn btn-red" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-cancel') }}', 'del')">
                                削除</a>
                        @else
                            <a class="btn btn-blue" onclick="friend_reqest({{ $friend->id }}, '{{ route('friend-request') }}', 'request')">
                                申請</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif


    </tbody>
</table>

{{--申請・承認・削除用フォーム--}}
<form name="friend_reqest_form" method="" action="">
    @csrf
    <input type="hidden" name="user_id" value="">
</form>


<script>
    
    document.addEventListener('DOMContentLoaded', function() {

    });

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

    function redirectToFriendShow(friend_id) {
        window.location.href = "{{ route('friend-show') }}?friend_id=" + friend_id;
    }

</script>