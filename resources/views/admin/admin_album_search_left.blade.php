{{--検索--}}
<form method="GET" action="{{ route('admin-album-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
        <div class="col-4 col-md-12">
            ・ｱﾙﾊﾞﾑ名
            <input type="text" name="search_album" class="form-control" value="{{$input['search_album'] ?? ''}}">
        </div>
        <div class="col-4 col-md-12">
            ・ｱｰﾃｨｽﾄ名
            <input type="text" name="search_artist" class="form-control" value="{{$input['search_artist'] ?? ''}}">
        </div>
        
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
</div>
</form>