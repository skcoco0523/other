
{{-- カテゴリ情報更新処理 --}}
<form method="POST" action="{{ route('admin-custom-category-reg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        <input type="hidden" name="id" value="">
        <div class="col-6 col-md-6">
            <label for="inputname" class="form-label">登録名</label>
            <input type="text" name="name" class="form-control" placeholder="name">
        </div>
        <div class="col-6 col-md-6">
            <label for="inputcategoryname" class="form-label">ビット番号</label>
            <input type="text" name="bit_num" class="form-control" placeholder="自動で付与されます" style="background-color: #f0f0f0; pointer-events: none;">
        </div>
        {{--
        <div class="col-sm">
            <label for="inputdispflag" class="form-label">表示有無</label>
            <select id="inputState" name="disp_flag" class="form-select">
                <option value="" {{ ($select->disp_flag ?? '') == '' ? 'selected' : '' }}></option>
                <option value="0" {{ ($select->disp_flag ?? '') == '0' ? 'selected' : '' }}>非表示</option>
                <option value="1" {{ ($select->disp_flag ?? '') == '1' ? 'selected' : '' }}>表示</option>
            </select>
        </div>
        --}}
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


{{--カテゴリ一覧--}}
@if(isset($custom_category))
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">#</th>
                <th scope="col" class="fw-light">登録名</th>
                <th scope="col" class="fw-light">ビット番号</th>
                <th scope="col" class="fw-light">表示順</th>
                <th scope="col" class="fw-light">表示状態</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
                <th scope="col" class="fw-light"></th>
            </tr>
            </thead>
            @foreach($custom_category as $category)
                <tr>
                    <td class="fw-light">{{$category->id}}</td>
                    <td class="fw-light">
                        <input id="name-{{$category->id}}" type="text" name="name" class="form-control" placeholder="name" value="{{$category->name}}">
                    </td>
                    <td class="fw-light">{{$category->bit_num}}</td>
                    <td class="fw-light">
                        {{$category->sort_num}}　　
                        <div class="btn-group btn-group-sm" role="group" aria-label="">
                            <input type="button" class="btn btn-secondary btn-sm" value="∧" onclick="category_sort_fnc('up','{{$category->id}}');" >
                            <input type="button" class="btn btn-secondary btn-sm" value="∨" onclick="category_sort_fnc('down','{{$category->id}}');" >
                        </div>
                    </td>
                    <td class="fw-light"> 
                        <select id="disp_flag-{{$category->id}}" name="sex" class="form-select">
                            <option value="0" {{ $category->disp_flag == '0' ? 'selected' : '' }}>非表示</option>
                            <option value="1" {{ $category->disp_flag == '1' ? 'selected' : '' }}>表示</option>
                        </select>
                    </td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $category->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $category->updated_at) !!}</td>
                    <td class="fw-light">
                        <input type="button" class="btn btn-primary" value="更新" onclick="category_chg_fnc('{{$category->id}}');" >
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{--表示順変更--}}
    <form name="category_sort_chg_form" method="POST" action="{{ route('admin-custom-category-sort-chg') }}">
        @csrf
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="id" value="">
    </form>
    {{--更新--}}
    <form name="category_chg_form" method="POST" action="{{ route('admin-custom-category-change') }}">
        @csrf
        <input type="hidden" name="id" value="">
        <input type="hidden" name="name" value="">
        <input type="hidden" name="disp_flag" value="">
    </form>
@endif



<script>
    //カテゴリ更新
    function category_chg_fnc(id){
        var trg = document.forms["category_chg_form"];

        trg.method="post";
        document.category_chg_form["id"].value  = id;

        after_name = document.getElementById("name-" + id).value;
        disp_flag = document.getElementById("disp_flag-" + id).value;

        document.category_chg_form["name"].value = after_name;
        document.category_chg_form["disp_flag"].value = disp_flag;
        trg.submit();
    }

    //カテゴリ表示順変更
    function category_sort_fnc(fnc,id){
        var trg = document.forms["category_sort_chg_form"];
        trg.method="post";
        document.category_sort_chg_form["fnc"].value    =fnc;
        document.category_sort_chg_form["id"].value     =id;
        trg.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
    });
</script>