
{{-- アーティスト情報更新処理 --}}
<form id="artist_chg_form" method="POST" action="{{ route('admin-artist-chg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        {{--検索条件--}}
        <input type="hidden" name="search_artist" value="{{$input['search_artist'] ?? ''}}">
        <input type="hidden" name="search_sex" value="{{$input['search_sex'] ?? ''}}">
        <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
        {{--対象データ--}}
        <input type="hidden" name="id" value="{{$select->id ?? ''}}">
        <div class="col-6 col-md-3">
            <label for="inputname" class="form-label">ｱｰﾃｨｽﾄ名(ﾒｲﾝ)</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$select->name ?? ''}}">
        </div>
        <div class="col-6 col-md-3">
            <label for="inputname2" class="form-label">ｱｰﾃｨｽﾄ名(ｻﾌﾞ)</label>
            <input type="text" name="name2" class="form-control" placeholder="name" value="{{$select->name2 ?? ''}}">
        </div>
        <div class="col-6 col-md-3">
            <label for="inputbirth" class="form-label">デビュー</label>
            <input type="date" max="9999-12-31" name="debut" class="form-control" value="{{$select->debut ?? ''}}">
        </div>
        <div class="col-6 col-md-3">
            <label for="inputsex" class="form-label">種別</label>
            <select id="inputState" name="sex" class="form-control">
                <option value="0" {{ ($select->sex ?? '') == '0' ? 'selected' : '' }}>ｸﾞﾙｰﾌﾟ</option>
                <option value="1" {{ ($select->sex ?? '') == '1' ? 'selected' : '' }}>男性</option>
                <option value="2" {{ ($select->sex ?? '') == '2' ? 'selected' : '' }}>女性</option>
            </select>
        </div>
    </div>
    
    <div class="text-end mb-3">
        <input type="submit" value="更新" class="btn btn-primary">
    </div>

</form>

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--アーティスト一覧--}}
@if(isset($artists))
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $page_prm = $input ?? '';
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $artists,'page_prm' => $page_prm,])
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">#</th>
                <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名(ﾒｲﾝ)</th>
                <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名(ｻﾌﾞ)</th>
                <th scope="col" class="fw-light">ｱﾙﾊﾞﾑ数</th>
                <th scope="col" class="fw-light">ﾃﾞﾋﾞｭｰ日</th>
                <th scope="col" class="fw-light">種別</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
                <th scope="col" class="fw-light"></th>
                <th scope="col" class="fw-light"></th>
            </tr>
            </thead>
            @foreach($artists as $artist)
                <tr>
                    <td class="fw-light">{{$artist->id}}</td>
                    <td class="fw-light">{{$artist->name}}</td>
                    <td class="fw-light">{{$artist->name2}}</td>
                    <td class="fw-light">
                        <a href="{{ route('admin-album-search', ['search_artist' => $artist->name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                        {{$artist->alb_cnt}}
                    </td>
                    <td class="fw-light">{{$artist->debut}}</td>
                    <td class="fw-light">
                        @if($artist->sex === 0)     ｸﾞﾙｰﾌﾟ
                        @elseif($artist->sex === 1) 男性
                        @elseif($artist->sex === 2) 女性
                        @endif
                    </td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $artist->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $artist->updated_at) !!}</td>
                    <td class="fw-light">
                        <input type="button" value="編集" class="btn btn-primary edit-btn">
                    </td>
                    <td class="fw-light">
                        <form method="POST" action="{{ route('admin-artist-del') }}">
                            @csrf
                            {{--検索条件--}}
                            <input type="hidden" name="search_artist" value="{{$input['search_artist'] ?? ''}}">
                            <input type="hidden" name="search_sex" value="{{$input['search_sex'] ?? ''}}">
                            <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
                            {{--対象データ--}}
                            <input type="hidden" name="id" value="{{$artist->id}}">
                            <input type="submit" value="削除" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $artists,'page_prm' => $page_prm,])
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('artist_chg_form');
    //更新フォームを非表示
    form.style.display = 'none';

    // 各行の編集ボタンにイベントリスナーを追加
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            // フォームを表示
            form.style.display = 'block';

            // ボタンの親要素（行）を取得
            const row       = this.closest('tr');
            const cells     = row.querySelectorAll('td');
            
            const id        = cells[0].textContent;
            const name      = cells[1].textContent;
            const name2     = cells[2].textContent;
            const debut     = cells[4].textContent;
            const sex       = cells[5].textContent.trim();

            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value    = id;
            document.querySelector('input[name="name"]').value  = name;
            document.querySelector('input[name="name2"]').value = name2;
            document.querySelector('input[name="debut"]').value = debut;
                    
        // 種別(性別)の選択肢を設定
        const selectSex = document.querySelector('select[name="sex"]');
            if (sex === 'ｸﾞﾙｰﾌﾟ')         selectSex.value = '0';
            else if (sex === '男性')    selectSex.value = '1';
            else if (sex === '女性')    selectSex.value = '2';
            else  selectSex.value = ''; // 種別の場合、空の値にする
        });
    });
});
</script>