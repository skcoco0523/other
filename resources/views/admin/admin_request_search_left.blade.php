{{--検索--}}
<form method="GET" action="{{ route('admin-request-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
        <div class="col-4 col-md-12">
            ・種別
            <select name="search_type" class="form-control">
                <option value=""  {{ ($input['search_type'] ?? '') == ''  ? 'selected' : '' }}></option>
                <option value="0" {{ ($input['search_type'] ?? '') == '0' ? 'selected' : '' }}>要望</option>
                <option value="1" {{ ($input['search_type'] ?? '') == '1' ? 'selected' : '' }}>問い合わせ</option>
            </select>
        </div>
        <div class="col-4 col-md-12">
            ・ステータス
            <select name="search_status" class="form-control">
                <option value=""  {{ ($input['search_status'] ?? '') == ''  ? 'selected' : '' }}></option>
                <option value="0" {{ ($input['search_status'] ?? '') == '0' ? 'selected' : '' }}>未対応</option>
                <option value="1" {{ ($input['search_status'] ?? '') == '1' ? 'selected' : '' }}>対応済</option>
            </select>
        </div>
        <div class="col-4 col-md-12">
            ・依頼内容
            <input type="text" name="search_mess" class="form-control" value="{{$input['search_mess'] ?? ''}}">
        </div>
        <div class="col-4 col-md-12">
            ・回答内容
            <input type="text" name="search_reply" class="form-control" value="{{$input['search_reply'] ?? ''}}">
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>