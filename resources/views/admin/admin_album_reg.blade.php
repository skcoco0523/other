{{-- アルバム登録処理 --}}
<form id="alb_reg_form" method="POST" action="{{ route('admin-album-reg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
    <div class="col-6 col-md-4">
            <label for="inputname" class="form-label">ｱﾙﾊﾞﾑ名</label>
            <input type="text" name="alb_name" class="form-control" placeholder="name" value="{{$input['alb_name'] ?? ''}}">
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
            <label for="inputrelease_date" class="form-label">ﾘﾘｰｽ</label>
            <input type="date" max="9999-12-31" name="release_date" class="form-control" value="{{$input['release_date'] ?? ''}}">
        </div>
    </div>

    <div class="row g-3 align-items-stretch mb-3">
        {{--曲一覧--}}
        <div class="col-12 col-md-6">
            <label for="inputart_music_list" class="form-label">楽曲リスト(改行込みで一括登録)　</label>
            <button type="button" onclick="processMusicList()">加工する</button>
            <textarea class="form-control" id="music_list" name="music_list">{{$input['music_list'] ?? ''}}</textarea>
        </div>
        <div class="col-12 col-md-6">
            <label for="affiliate-link" class="form-label">リンク：</label>
            <a href="https://pub.a8.net/a8v2/media/memberAction.do" target="_blank" rel="noopener noreferrer" class="form-label me-3">A8.net</a>
            <a href="https://affiliate.rakuten.co.jp/?l-id=af_header_logo" target="_blank" rel="noopener noreferrer" class="form-label">楽天アフィリエイト</a>
            <textarea class="form-control" id="affiliate-link" name="aff_link">{{$input['aff_link'] ?? ''}}</textarea>
            
            <div class="form-group mt-3">
                <label class="form-label">
                    イメージ(上記リンクのイメージが表示)
                    <input type="hidden" name="no_link" value="0">
                    <!-- チェックボックス -->
                    <input type="checkbox" class="form-check-input ms-2" name="no_link" value="1">
                    <span class="ms-1">リンクなしで登録</span>
                </label>
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

{{--アルバム登録履歴--}}
@if(isset($albums))
    
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">ｱﾙﾊﾞﾑ名</th>
                <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名</th>
                <th scope="col" class="fw-light">収録数</th>
                <th scope="col" class="fw-light">ﾘﾘｰｽ</th>
                <th scope="col" class="fw-light">ﾃﾞｰﾀ登録日</th>
                <th scope="col" class="fw-light">ﾃﾞｰﾀ更新日</th>
                <th scope="col" class="fw-light">ｲﾒｰｼﾞ&ﾘﾝｸ</th>
            </tr>
            </thead>
            @foreach($albums as $album)
                <tr>
                    <td class="fw-light">{{$album->name}}</td>
                    <td class="fw-light">{{$album->art_name}}</td>
                    <td class="fw-light">{{$album->mus_cnt}}</td>
                    <td class="fw-light">{{$album->release_date}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $album->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $album->updated_at) !!}</td>
                    <td class="fw-light">
                        <a class="icon-55" href="{{ $album->href }}">
                            <img src="{{ $album->src }}" style="object-fit: contain; width: 100%; height: 100%;" alt="album_image">
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

<script>

function processMusicList() {
    let musicList = document.getElementById('music_list').value;
    let lines = musicList.split('\n');
    
    // すべての行が「XX.」形式に該当するかチェック
    let datachack1 = lines.every(function(line) { return /^\s*\d+\.\s*/.test(line);         });
    // すべての行が「XX 」形式に該当するかチェック
    let datachack2 = lines.every(function(line) { return /^\s*\d+\ \s*/.test(line);         });
    // すべての行が「XX　」形式に該当するかチェック
    let datachack3 = lines.every(function(line) { return /^\s*\d+\　\s*/.test(line);        });
    // すべての行が「[X:XX]」形式に該当するかチェック
    let datachack4 = lines.every(function(line) { return /\s*\[\d+:\d+\]\s*$/.test(line);   });

    // 加工された曲リストを作成
    let processedLines = lines.map(function(line) {
        // すべての行に「XX.」がある場合は削除
        if (datachack1) { line = line.replace(/^\s*\d+\.\s*/, ''); }
        if (datachack2) { line = line.replace(/^\s*\d+\ \s*/, ''); }
        if (datachack3) { line = line.replace(/^\s*\d+\　\s*/, ''); }
        if (datachack4) { line = line.replace(/\s*\[\d+:\d+\]\s*$/, ''); }
        return line;
    });

    // 加工されたリストをテキストエリアに表示
    document.getElementById('music_list').value = processedLines.join('\n');
}

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
    document.getElementById('alb_reg_form').addEventListener('submit', function(event) {
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
