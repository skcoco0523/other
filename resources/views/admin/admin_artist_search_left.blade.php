{{--検索--}}
<form method="GET" action="{{ route('admin-artist-search') }}">
    
    検索条件
    <div class="row g-3 align-items-end">
        <div class="col-4 col-md-12">
            ・ｱｰﾃｨｽﾄ名
            <input type="text" name="search_artist" class="form-control" value="{{$input['search_artist'] ?? ''}}">
        </div>    
        <div class="col-4 col-md-12">
            ・種別
            <select name="search_sex" class="form-control">
                <option value=""  {{ ($input['search_sex'] ?? '') == ''  ? 'selected' : '' }}></option>
                <option value="0" {{ ($input['search_sex'] ?? '') == '0' ? 'selected' : '' }}>ｸﾞﾙｰﾌﾟ</option>
                <option value="1" {{ ($input['search_sex'] ?? '') == '1' ? 'selected' : '' }}>男性</option>
                <option value="2" {{ ($input['search_sex'] ?? '') == '2' ? 'selected' : '' }}>女性</option>
            </select>
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>