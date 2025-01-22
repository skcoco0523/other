{{-- おすすめ登録処理 --}}
<form method="POST" action="{{ route('admin-recommend-reg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-6 col-md-6">
            <label for="inputname" class="form-label">登録名</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$input['name'] ?? ''}}">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
        </div>
        <div class="col-6 col-md-6">
            <label for="inputcategory" class="form-label">カテゴリ</label>
            <select id="inputState" name="category" class="form-control">
                <option value="0" {{ ($input['category'] ?? '') == '0' ? 'selected' : '' }}>曲</option>
                <option value="1" {{ ($input['category'] ?? '') == '1' ? 'selected' : '' }}>アーティスト</option>
                <option value="2" {{ ($input['category'] ?? '') == '2' ? 'selected' : '' }}>アルバム</option>
                <option value="3" {{ ($input['category'] ?? '') == '3' ? 'selected' : '' }}>プレイリスト</option>
            </select>
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

{{--あすすめ登録履歴--}}
@if(isset($recommend))
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">登録名</th>
                <th scope="col" class="fw-light">カテゴリ</th>
                <th scope="col" class="fw-light">登録数</th>
                <th scope="col" class="fw-light">表示順</th>
                <th scope="col" class="fw-light">登録者</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
            </tr>
            </thead>
            @foreach($recommend as $recom)
                <tr>
                    <td class="fw-light">{{$recom->name}}</td>
                    <td class="fw-light">
                    @if($recom->category === 0)         曲
                        @elseif($recom->category === 1) アーティスト
                        @elseif($recom->category === 2) アルバム
                        @elseif($recom->category === 3) プレイリスト
                    @endif
                    </td>
                    <td class="fw-light">{{$recom->detail_cnt}}</td>
                    <td class="fw-light">{{$recom->sort_num}}</td>
                    <td class="fw-light">{{$recom->user_name}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $recom->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $recom->updated_at) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
