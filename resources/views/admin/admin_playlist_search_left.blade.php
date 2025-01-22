{{--検索--}}
<form method="GET" action="{{ route('admin-playlist-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
        <div class="col-4 col-md-12">
            ・ﾌﾟﾚｲﾘｽﾄ名
            <input type="text" name="search_playlist" class="form-control" value="{{$input['search_playlist'] ?? ''}}">
        </div>
        <div class="col-4 col-md-12">
            ・種別
            <select name="search_admin_flag" class="form-control">
                <option value=""  {{ ($input['search_admin_flag'] ?? '') == ''  ? 'selected' : '' }}></option>
                <option value="1" {{ ($input['search_admin_flag'] ?? '') == '1' ? 'selected' : '' }}>管理者</option>
                <option value="0" {{ ($input['search_admin_flag'] ?? '') == '0' ? 'selected' : '' }}>ユーザー</option>
            </select>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>