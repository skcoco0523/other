{{--検索--}}
<form method="GET" action="{{ route('admin-recommend-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
        <div class="col-4 col-md-12">
            ・登録名
            <input type="text" name="search_recommend" class="form-control" value="{{$input['search_recommend'] ?? ''}}" placeholder="検索(登録名)">
        </div>
        <div class="col-4 col-md-12">
            ・カテゴリ
            <select name="search_category" class="form-control" placeholder="カテゴリ">
                <option value=""  {{ ($input['search_category'] ?? '') == '' ? 'selected' : '' }}></option>
                <option value="0" {{ ($input['search_category'] ?? '') == '0' ? 'selected' : '' }}>曲</option>
                <option value="1" {{ ($input['search_category'] ?? '') == '1' ? 'selected' : '' }}>アーティスト</option>
                <option value="2" {{ ($input['search_category'] ?? '') == '2' ? 'selected' : '' }}>アルバム</option>
                <option value="3" {{ ($input['search_category'] ?? '') == '3' ? 'selected' : '' }}>プレイリスト</option>
            </select>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>