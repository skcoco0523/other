{{-- メモ登録処理 --}}
<form id="mus_reg_form" method="POST" action="{{ route('admin-memo-reg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-6 col-md-4">
            <label for="inputname" class="form-label">タイトル</label>
            <input type="text" name="title" class="form-control" placeholder="title" value="{{$input['title'] ?? ''}}">
        </div>
        <div class="col-6 col-md-4">
            <label for="inputname" class="form-label">内容</label>
            <textarea name="content" class="form-control" placeholder="メモの内容" rows="5">{{$input['content'] ?? ''}}</textarea>
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

{{--IoTデバイス一覧--}}
@if(isset($admin_memo_list))

    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">タイトル</th>
                <th scope="col" class="fw-light">内容</th>
                <th scope="col" class="fw-light">更新日時</th>
                <th scope="col" class="fw-light"></th>
                <th scope="col" class="fw-light"></th>
            </tr>
            </thead>
            @foreach($admin_memo_list as $memo)
                <tr>
                    <td class="fw-light">{{$memo->title}}</td>
                    <td class="fw-light">{!! nl2br(e($memo->content)) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $memo->updated_at) !!}</td>
                    <td class="fw-light">
                        <input type="button" value="編集" class="btn btn-primary edit-btn">
                    </td>
                    <td class="fw-light">
                        <form method="POST" action="{{ route('admin-memo-del') }}">
                            @csrf
                            {{--検索条件--}}
                            <input type="hidden" name="search_title" value="{{$input['search_title'] ?? ''}}">
                            <input type="hidden" name="search_content" value="{{$input['search_content'] ?? ''}}">
                            {{--対象データ--}}
                            <input type="hidden" name="id" value="{{$memo->id}}">
                            <input type="submit" value="削除" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    
@endif

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('remoteblade_chg_form');
        //更新フォームを非表示
        form.style.display = 'none';

        // 各行の編集ボタンにイベントリスナーを追加
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                // フォームを表示
                form.style.display = 'block';

                // ボタンの親要素（行）を取得
                const row           = this.closest('tr');
                const cells         = row.querySelectorAll('td');
                
                const id            = cells[0].textContent.trim();
                const remote_kind   = cells[1].textContent.trim();
                const remote_kind_value = remoteKindMap[remote_kind] ?? '';
                const blade_name    = cells[2].textContent;
                const test_flag     = cells[3].textContent.trim();
                // フォームの対応するフィールドにデータを設定
                document.querySelector('input[name="id"]').value            = id;
                document.querySelector('select[name="remote_kind"]').value  = remote_kind_value;
                document.querySelector('input[name="blade_name"]').value    = blade_name;
                document.querySelector('select[name="test_flag"]').value    = test_flag;
            });
        });
        
        
    });

</script>