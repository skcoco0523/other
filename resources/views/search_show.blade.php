@extends('layouts.app')

<?//コンテンツ?>  
@section('content')

<form action="{{ route('search-list-show') }}" method="GET" id="searchForm">
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="search" id="searchInput" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="曲、アーティスト、アルバムなど" aria-describedby="basic-addon1">
    </div>
</form>

{{-- 検索結果の補完候補 --}}
<div id="searchSuggestions" style="display: none;">
    <table class="table table-borderless table-center">
        <tbody id="suggestionsList">
            {{-- 検索結果をここに動的に追加 --}}
        </tbody>
    </table>
</div>
<?//入力時は以下を非表示にする?>
@auth
    <div id="historyList">
        <h4>履歴</h4>
        @foreach ($history as $his)
            <table class="table table-borderless table-center">
                <tbody>
                <tr><td>
                    <span onclick="redirectToSearch('{{ $his->search_word }}');">
                        {{Str::limit($his->search_word, 25, '...')}}
                    </span>
                    <i class="fa-solid fa-magnifying-glass" style="float: right; cursor: pointer;"></i>
                </td></tr>
                </tbody>
            </table>
        @endforeach
        <div class="d-flex overflow-auto justify-content-center contents_box">
            <a class="nav-link nav-link-red" onclick="delete_history()">検索履歴を削除</a>
        </div>
@endauth

<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');     //キーワード
        const searchForm = document.getElementById('searchForm');       // 検索フォーム
        const suggestionsList = document.getElementById('suggestionsList');
        const historyList = document.getElementById('historyList');

        const historyDeleteUrl = "{{ route('history-delete') }}";       //search_history.jsで利用するurl

        function showSuggestions() {
            searchSuggestions.style.display = 'block';
            historyList.style.display = 'none'; // 履歴を非表示にする
        }

        function hideSuggestions() {
            searchSuggestions.style.display = 'none';
            historyList.style.display = 'block'; // 履歴を表示する
        }
        
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();
            if (query.length === 0) {
                hideSuggestions();
            } else {
                fetchSuggestions(query);
            }
        });

        function fetchSuggestions(query) {
    fetch(`{{ route('search-suggestions') }}?keyword=${query}`)
        .then(response => response.json())
        .then(data => {
            suggestionsList.innerHTML = '';
            const keys = Object.keys(data);
            if (keys.length > 0) {
                keys.forEach(key => {
                    let item = data[key]; // キーを使って値を取得
                        // itemの内容が25文字以上の場合、省略
                    const maxLength = 25;
                    if (item.length > maxLength) {
                        item = item.slice(0, maxLength) + '...';
                    }
                    const suggestionItem = document.createElement('tr');
                    suggestionItem.innerHTML = `
                        <td class="col-12">
                            <span>${item}</span>
                            <i class="fa-solid fa-magnifying-glass" style="float: right;"></i>
                        </td>`;
                    suggestionItem.addEventListener('click', function() {
                        //２５文字以上で「...」で終了しているｷｰﾜｰﾄﾞの場合は加工して反映する
                        if(item.length >= 25){
                            item = item.replace(/\.\.\.$/, "");
                        }
                        searchInput.value = item;
                        searchForm.submit(); // フォームを送信
                    });
                    suggestionsList.appendChild(suggestionItem);
                });
                showSuggestions();
            } else {
                suggestionsList.innerHTML = '<tr><td>一致する結果がありません。</td></tr>';
                showSuggestions();
            }
        })
        .catch(error => {
        });
}
    });
</script>

<style>
</style>
