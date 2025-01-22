
{{-- ミュージック情報更新処理 --}}
<form id="mus_chg_form" method="POST" action="{{ route('admin-music-chg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        {{--検索条件--}}
        <input type="hidden" name="search_music" value="{{$input['search_music'] ?? ''}}">
        <input type="hidden" name="search_artist" value="{{$input['search_artist'] ?? ''}}">
        <input type="hidden" name="search_album" value="{{$input['search_album'] ?? ''}}">
        <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
        {{--対象データ--}}
        <input type="hidden" id="id" name="id" value="{{$select->id ?? ($input['id'] ?? '')}}">
        <input type="hidden" id="selectedArtistId" name="art_id" value="{{$select->art_id ?? ($input['art_id'] ?? '')}}">
        <input type="hidden" name="aff_id" value="{{$select->aff_id ?? ($input['aff_id'] ?? '')}}">
        <div class="col-4 col-md-4">
            <label for="inputname" class="form-label">曲名</label>
            <input type="text" name="mus_name" class="form-control" placeholder="name" value="{{ $select->name ?? ($input['mus_name'] ?? '') }}">
        </div>
        <div class="col-4 col-md-4">
            <label for="inputart_name" class="form-label">ｱｰﾃｨｽﾄ名</label>
            <input class="form-control" list="artistSelect" name="art_name" placeholder="Artist to search..." value="{{$input['art_name'] ?? ''}}" autocomplete="off">
            <datalist id="artistSelect">
                @foreach($artists as $artist)
                <option value="{{ $artist->name }}" data-id="{{ $artist->id }}">{{ $artist->name }}</option>
                @endforeach
            </datalist>
        </div>
        <div class="col-4 col-md-4">
            <label for="inputbirth" class="form-label">ﾘﾘｰｽ</label>
            <input type="date" max="9999-12-31" name="release_date" class="form-control" value="{{$select->release_date ?? ($input['release_date'] ?? '') }}">
        </div>
    </div>

    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-12 col-md-6">
            {{-- 外部リンクは未使用
                <label for="inputlink" class="form-label">ﾘﾝｸ</label>
                <input type="text" name="link" class="form-control" placeholder="https://..." value="{{$input['link'] ?? ''}}">
            --}}
        </div>
        <div class="col-12 col-md-6">
            <label for="affiliate-link" class="form-label">リンク：</label>
            <a href="https://pub.a8.net/a8v2/media/memberAction.do" target="_blank" rel="noopener noreferrer" class="form-label me-3">A8.net</a>
            <a href="https://affiliate.rakuten.co.jp/?l-id=af_header_logo" target="_blank" rel="noopener noreferrer" class="form-label">楽天アフィリエイト</a>
            <textarea class="form-control" id="affiliate-link" name="aff_link">{{$input['aff_link'] ?? ''}}</textarea>
            
            <div class="form-group mt-3">
                <label class="form-label">イメージ(上記リンクのイメージが表示)</label>
            </div>
            <div id="affiliate-preview">
                {{--入力されたリンクが表示される部分--}}
            </div>
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

{{--曲一覧--}}
@if(isset($musics))
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $page_prm = $input ?? '';
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $musics,'page_prm' => $page_prm,])
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">#</th>
                <th scope="col" class="fw-light">曲名</th>
                <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名</th>
                <th scope="col" class="fw-light">アルバム名</th>
                <th scope="col" class="fw-light">ﾘﾘｰｽ</th>
                <th scope="col" class="fw-light">ﾘﾝｸ</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
                <th scope="col" class="fw-light">ｲﾒｰｼﾞ&ﾘﾝｸ</th>
                <th scope="col" class="fw-light"></th>
                <th scope="col" class="fw-light"></th>
            </tr>
            </thead>
            @foreach($musics as $music)
                <tr>
                    <input type="hidden" name="art_id" value="{{$music->art_id}}">
                    <td class="fw-light">{{$music->id}}</td>
                    <td class="fw-light">{{$music->name}}</td>
                    <td class="fw-light">
                        <a href="{{ route('admin-artist-search', ['search_artist' => $music->art_name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                        {{$music->art_name}}
                    </td>
                    <td class="fw-light">
                        <a href="{{ route('admin-album-search', ['search_album' => $music->alb_name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                        {{$music->alb_name}}
                    </td>
                    <td class="fw-light">{{$music->release_date}}</td>
                    <td class="fw-light">{{$music->link}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $music->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $music->updated_at) !!}</td>
                    <td class="fw-light">
                        <a class="icon-55" href="{{ $music->href }}">
                            <img src="{{ $music->src }}" style="object-fit: contain; width: 100%; height: 100%;" alt="music_image">
                        </a>
                    </td>
                    <td class="fw-light">
                        <input type="button" value="編集" class="btn btn-primary edit-btn">
                    </td>
                    <td class="fw-light">
                        <form method="POST" action="{{ route('admin-music-del') }}">
                            @csrf
                            {{--検索条件--}}
                            <input type="hidden" name="search_music" value="{{$input['search_music'] ?? ''}}">
                            <input type="hidden" name="search_artist" value="{{$input['search_artist'] ?? ''}}">
                            <input type="hidden" name="search_album" value="{{$input['search_album'] ?? ''}}">
                            <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
                            {{--対象データ--}}
                            <input type="hidden" name="id" value="{{$music->id}}">
                            <input type="hidden" name="name" value="{{$music->name}}">
                            <input type="hidden" name="aff_id" value="{{$music->aff_id}}">
                            <input type="submit" value="削除" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $musics,'page_prm' => $page_prm,])
@endif

<script>

document.addEventListener('DOMContentLoaded', function () {

    var affiliateLinkInput = document.getElementById('affiliate-link');
    var affiliatePreview = document.getElementById('affiliate-preview');

    const form = document.getElementById('mus_chg_form');
    //更新フォームを非表示
    form.style.display = 'none';

    affiliateLinkInput.addEventListener('input', function () {
        var link = affiliateLinkInput.value.trim();
        if (link !== '') {
            // 入力されたリンクをそのまま表示する
            affiliatePreview.innerHTML = link;
        } else {
            // クリア
            affiliatePreview.innerHTML = '';
        }
    });

    // 各行の編集ボタンにイベントリスナーを追加
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            // フォームを表示
            form.style.display = 'block';

            // ボタンの親要素（行）を取得
            const row           = this.closest('tr');
            const cells         = row.querySelectorAll('td');
            
            const id            = cells[0].textContent;
            const mus_name      = cells[1].textContent;
            const art_name      = cells[2].textContent;
            const release_date  = cells[4].textContent;
            const link          = cells[5].textContent;
            const art_id_input  = row.querySelector('input[name="art_id"]');
            const art_id_value  = art_id_input.value;
            const aff_id_input  = row.querySelector('input[name="aff_id"]');
            const aff_id_value  = aff_id_input.value;
            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value            = id;
            document.querySelector('input[name="mus_name"]').value      = mus_name;
            document.querySelector('input[name="art_name"]').value      = art_name;
            document.querySelector('input[name="release_date"]').value  = release_date;
            document.querySelector('input[name="link"]').value          = link;
            document.querySelector('input[name="art_id"]').value        = art_id_value; // art_idの値を設定
            document.querySelector('input[name="aff_id"]').value        = aff_id_value; // aff_idの値を設定
        });
    });
    
    //リストから選択時、art_idをpostできないため、再取得
    form.addEventListener('submit', function(event) {
        var artistInput = document.querySelector('input[name="art_name"]');
        var art_idInput = document.getElementById('selectedArtistId');
        
        // アーティストが入力されている場合のみ、フォームに値を設定する
        if (artistInput.value !== '') {
            var artistSelect = document.getElementById('artistSelect');
            var selectedOption = Array.from(artistSelect.options).find(option => option.value === artistInput.value);
            if (selectedOption) {
                art_idInput.value = selectedOption.dataset.id;
            }
        }
    });
});
</script>

