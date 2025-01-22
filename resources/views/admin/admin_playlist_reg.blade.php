{{-- プレイリスト登録処理 --}}
<form method="POST" action="{{ route('admin-playlist-reg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-12 col-md-12">
            <label for="inputname" class="form-label">ﾌﾟﾚｲﾘｽﾄ名</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$input['name'] ?? ''}}">
            <input type="hidden" name="admin_flag" value="1">
        </div>
    </div>

    <div class="text-end mb-3">
        <input type="submit" value="登録" class="btn btn-primary">
    </div>

</form>

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--プレイリスト登録履歴--}}
@if(isset($playlist))
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">ﾌﾟﾚｲﾘｽﾄ名</th>
                <th scope="col" class="fw-light">登録曲数</th>
                <th scope="col" class="fw-light">登録者</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
            </tr>
            </thead>
            @foreach($playlist as $pl)
                <tr>
                    <td class="fw-light">{{$pl->name}}</td>
                    <td class="fw-light">{{$pl->mus_cnt}}</td>
                    <td class="fw-light">{{$pl->user_name}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $pl->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $pl->updated_at) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
