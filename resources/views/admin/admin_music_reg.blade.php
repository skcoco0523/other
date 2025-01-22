
{{-- 曲登録処理 --}}
<form id="mus_reg_form" method="POST" action="{{ route('admin-music-reg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-6 col-md-4">
            <label for="inputname" class="form-label">曲名</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$input['name'] ?? ''}}">
        </div>
        <div class="col-6 col-md-4">
            <label for="inputart_name" class="form-label">ｱｰﾃｨｽﾄ名</label>
            <input class="form-control" list="artistSelect" name="art_name" placeholder="Artist to search..." value="{{$input['art_name'] ?? ''}}" autocomplete="off">
            <input type="hidden" id="selectedArtistId" name="art_id">
            <datalist id="artistSelect">
                @foreach($artists as $artist)
                <option value="{{ $artist->name }}" data-id="{{ $artist->id }}">{{ $artist->name }}</option>
                @endforeach
            </datalist>
        </div>
        <div class="col-6 col-md-4">
            <label for="inputbirth" class="form-label">ﾘﾘｰｽ</label>
            <input type="date" max="9999-12-31" name="release_date" class="form-control" value="{{$input['release_date'] ?? ''}}">
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
                    <input type="hidden" name="no_link" value="0">
                    <!-- チェックボックス -->
                    <input type="checkbox" class="form-check-input ms-2" name="no_link" value="1">
                    <span class="ms-1">リンクなしで登録</span>
            </div>
            <div id="affiliate-preview">
                {{--入力されたリンクが表示される部分--}}
            </div>
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

{{--曲登録履歴--}}
@if(isset($musics))
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">曲名</th>
                <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名</th>
                <th scope="col" class="fw-light">ﾘﾘｰｽ日</th>
                <th scope="col" class="fw-light">ﾘﾝｸ</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
                <th scope="col" class="fw-light">ｲﾒｰｼﾞ&ﾘﾝｸ</th>
            </tr>
            </thead>
            @foreach($musics as $music)
                <tr>
                    <td class="fw-light">{{$music->name}}</td>
                    <td class="fw-light">{{$music->art_name}}</td>
                    <td class="fw-light">{{$music->release_date}}</td>
                    <td class="fw-light">{{$music->link}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $music->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $music->updated_at) !!}</td>
                    <td class="fw-light">
                        <a class="icon-55" href="{{ $music->href }}">
                            <img src="{{ $music->src }}" style="object-fit: contain; width: 100%; height: 100%;" alt="music_image">
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    var affiliateLinkInput = document.getElementById('affiliate-link');
    var affiliatePreview = document.getElementById('affiliate-preview');

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
    //リストから選択時、art_idをpostできないため、再取得
    document.getElementById('mus_reg_form').addEventListener('submit', function(event) {
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
