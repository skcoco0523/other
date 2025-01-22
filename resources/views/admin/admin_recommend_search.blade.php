
{{-- おすすめ情報更新処理 --}}
@if(!(isset($recommend_detail)))
    <form id="recom_chg_form" method="POST" action="{{ route('admin-recommend-chg') }}">
        @csrf
        <div class="row g-3 align-items-stretch mb-3">
            {{--検索条件--}}
            <input type="hidden" name="search_recommend" value="{{$input['search_recommend'] ?? ''}}">
            <input type="hidden" name="search_category" value="{{$input['search_category'] ?? ''}}">
            <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
            {{--対象データ--}}
            <input type="hidden" name="id" value="{{$select->id ?? ''}}">
            <div class="col-6 col-md-3">
                <label for="inputname" class="form-label">登録名</label>
                <input type="text" name="name" class="form-control" placeholder="name" value="{{$select->name ?? ''}}">
            </div>
            <div class="col-6 col-md-3">
                <label for="inputcategoryname" class="form-label">カテゴリ</label>
                <input type="text" name="category_name" class="form-control" value="{{$select->category ?? ''}}" style="background-color: #f0f0f0; pointer-events: none;">
                <input type="hidden" name="category" class="form-control" value="{{$input['category'] ?? ''}}">
            </div>
            <div class="col-6 col-md-3">
                <label for="inputdispflag" class="form-label">表示有無</label>
                <select id="inputState" name="disp_flag" class="form-control">
                    <option value="" {{ ($select->disp_flag ?? '') == '' ? 'selected' : '' }}></option>
                    <option value="0" {{ ($select->disp_flag ?? '') == '0' ? 'selected' : '' }}>非表示</option>
                    <option value="1" {{ ($select->disp_flag ?? '') == '1' ? 'selected' : '' }}>表示</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label for="inputusername" class="form-label">登録者</label>
                <input type="text" name="user_name" class="form-control" value="{{$select->user_name ?? ''}}" style="background-color: #f0f0f0; pointer-events: none;">
            </div>
        </div>
        
        <div class="text-end mb-3">
            <input type="submit" value="更新" class="btn btn-primary">
        </div>

    </form>
@endif

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--おすすめ一覧--}}
@if(isset($recommend))
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $page_prm = $input ?? '';
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $recommend,'page_prm' => $page_prm,])
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">#</th>
                <th scope="col" class="fw-light">登録名</th>
                <th scope="col" class="fw-light">カテゴリ</th>
                <th scope="col" class="fw-light">登録数</th>
                <th scope="col" class="fw-light">表示順</th>
                <th scope="col" class="fw-light">表示状態</th>
                <th scope="col" class="fw-light">登録者</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
                <th scope="col" class="fw-light">詳細変更</th>
                <th scope="col" class="fw-light"></th>
                <th scope="col" class="fw-light"></th>
            </tr>
            </thead>
            @foreach($recommend as $recom)
                <tr>
                    <td class="fw-light">{{$recom->id}}</td>
                    <td class="fw-light">{{$recom->name}}</td>
                    <td class="fw-light">
                    @if($recom->category === 0)     曲
                    @elseif($recom->category === 1) アーティスト
                    @elseif($recom->category === 2) アルバム
                    @elseif($recom->category === 3) プレイリスト
                    @endif
                    </td>
                    <td class="fw-light">{{$recom->detail_cnt}}</td>
                    <td class="fw-light">
                        {{$recom->sort_num}}　　
                        {{--カテゴリ検索時のみ表示順変更可能--}}
                        @if($input['category']!=null)
                        <div class="btn-group btn-group-sm" role="group" aria-label="">
                            <input type="button" class="btn btn-secondary btn-sm" value="∧" onclick="recom_sort_fnc('up','{{$recom->id}}');" >
                            <input type="button" class="btn btn-secondary btn-sm" value="∨" onclick="recom_sort_fnc('down','{{$recom->id}}');" >
                        </div>
                        @endif
                    </td>
                    <td class="fw-light">
                    @if($recom->disp_flag === 0)         非表示
                        @elseif($recom->disp_flag === 1) 表示
                    @endif
                    </td>
                    <td class="fw-light">{{$recom->user_name}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $recom->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $recom->updated_at) !!}</td>
                    <td class="fw-light">
                        <form method="GET" action="{{ route('admin-recommend-chgdetail') }}">
                            <input type="hidden" name="id" value="{{$recom->id}}">
                            <input type="hidden" name="category" value="{{$recom->category}}">
                            <input type="submit" value="詳細変更" class="btn btn-primary">
                        </form>
                    </td>
                    <td class="fw-light">
                        <input type="button" value="編集" class="btn btn-primary edit-btn">
                    </td>
                    <td class="fw-light">
                        <form method="POST" action="{{ route('admin-recommend-del') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{$recom->id}}">
                            <input type="hidden" name="recom_name" value="{{$recom->name}}">
                            <input type="hidden" name="category" value="{{$input['category'] ?? ''}}">
                            <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
                            <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
                            <input type="submit" value="削除" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('admin.layouts.pagination', ['paginator' => $recommend,'page_prm' => $page_prm,])

    {{--表示順変更--}}
    <form name="recom_sort_chg_form" method="POST" action="{{ route('admin-recommend-sort-chg') }}">
        @csrf
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="search_category" value={{$input['search_category']}}>
        <input type="hidden" name="category" value={{$input['category']}}>
    </form>
@endif

{{--収録曲変更--}}
@if(isset($recommend_detail))
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $page_prm = $input ?? '';
    @endphp
    <div class="g-3 mb-3">
        <label for="inputname" class="form-label">おすすめ登録名</label>
        <input class="form-control" type="text" placeholder="{{$recommend_detail->name ?? ''}}" disabled>
    </div>

    <div class="row g-3 align-items-stretch mb-3">
        
        <div class="col-12 col-md-6">
            <label for="inputmusic" class="form-label">収録曲</label>
            <div style="max-height: 600px; overflow-y: auto;">
                {{--変更フォーム--}}
                @foreach($recommend_detail as $dtl)
                <div class="row">
                    <div class="col-sm-9"> <!-- フォームの列 -->
                    @if(isset($dtl->name))
                        @if($input['category']==0 || $input['category']==2)
                        <input type="text" class="form-control" value="{{$dtl->name}}    < {{$dtl->art_name}} >" disabled>
                        @else
                        <input type="text" class="form-control" value="{{$dtl->name}}" disabled>
                        @endif
                    @else
                    <input type="text" class="form-control" value="データなし" disabled>
                    @endif
                    </div>
                    <div class="col-sm-3 mb-2"> <!-- ボタンの列 -->
                        <div class="d-sm-inline-flex"> <!-- スクリーン幅が小さいときにインラインフレックスにする -->
                            <input type="button" class="btn btn-danger" value="削除" onclick="recom_detail_fnc('del','{{$dtl->id}}');" >
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-12 col-md-6">
        {{--追加用  検索フォーム--}}
        <form method="GET" action="{{ route('admin-recommend-detail-search') }}">

        <input type="hidden" name="id" value="{{$recommend_detail->id}}">
        <input type="hidden" name="category" value="{{$input['category']}}">
        <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
            <div class="row g-3 align-items-stretch mb-3">
                <div class="col-sm-6">
                    <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索({{$recommend_detail->item1}})">
                </div>
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-success">検索</button>
                </div>
            </div>
        </form>
        {{--追加用テーブル--}}
        @if(isset($detail) && is_iterable($detail))
            {{--ﾍﾟｰｼﾞｬｰ--}}
            {{--@include('admin.layouts.pagination', ['paginator' => $detail,'page_prm' => $page_prm,])--}}
            <div style="overflow-x: auto;">
                <table class="table table-striped table-hover table-bordered fs-6 ">
                    <thead>
                    <tr>
                        <th scope="col" class="fw-light">#</th>
                        <th scope="col" class="fw-light">{{$recommend_detail->item1}}</th>
                        @if($input['category']==0 || $input['category']==2)
                        <th scope="col" class="fw-light">アーティスト名</th>
                        @endif
                        <th scope="col" class="fw-light"></th>
                    </tr>
                    </thead>
                    @foreach($detail as $dtl)
                        <tr>
                            <td class="fw-light">{{$dtl->id}}</td>
                            <td class="fw-light">
                                @if($input['category']==0)
                                    <a href="{{ route('admin-music-search', ['search_music' => $dtl->name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                                @endif
                                @if($input['category']==1)
                                    <a href="{{ route('admin-artist-search', ['search_artist' => $dtl->name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                                @endif
                                @if($input['category']==2)
                                    <a href="{{ route('admin-album-search', ['search_album' => $dtl->name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                                @endif
                                @if($input['category']==3)
                                    <a href="{{ route('admin-playlist-search', ['search_playlist' => $dtl->name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                                @endif
                                {{$dtl->name}}
                            </td>
                        @if($input['category']==0 || $input['category']==2)
                            <td class="fw-light">
                                <a href="{{ route('admin-artist-search', ['search_artist' => $dtl->art_name] )}}" class="text-decoration-none" rel="noopener noreferrer">
                                {{$dtl->art_name}}
                            </td>
                        @endif
                            <td class="fw-light">
                                <input type="button" class="btn btn-success" value="追加" onclick="recom_detail_fnc('add','{{$dtl->id}}');" >
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{--ﾍﾟｰｼﾞｬｰ--}}
            @include('admin.layouts.pagination', ['paginator' => $detail,'page_prm' => $page_prm,])
        @endif 

        </div>
    </div>

    </div>



{{--削除・追加用フォーム--}}
<form name="detail_form" method="POST" action="{{ route('admin-recommend-chgdetail-fnc') }}">
    @csrf
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="id" value="{{$recommend_detail->id}}">
    <input type="hidden" name="category" value="{{$input['category']}}">
    <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
    <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
    <input type="hidden" name="detail_id" value="">
</form>

@endif

<script>
    function recom_detail_fnc(fnc,id){
        if (fnc === 'del') {
            var rtn = confirm('削除してもよろしいですか？');
            if (rtn === false) {
                return false;
            }
        }
        var trg = document.forms["detail_form"];
        trg.method="post";
        document.detail_form["fnc"].value     =fnc;
        if (fnc === 'del') {
            document.detail_form["detail_id"].value  =id;
            //var chg_name =document.getElementsByName("name_" + mus_id)[0].value;
            //document.detail_form["name"].value    =chg_name;

        }else if(fnc === 'add'){
            document.detail_form["detail_id"].value  =id;
            //var add_name =document.getElementsByName("add_name")[0].value;
            //document.detail_form["name"].value    =add_name;
        }
        trg.submit();
    }
    //変更フォームの表示切替変更フォームの表示切替
    function toggleDetails_chg() {
        var element = document.getElementById("detail_chg");
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
    function toggleDetails_add() {
        var element = document.getElementById("detail_add");
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
    //お気に入り表示順変更
    function recom_sort_fnc(fnc,recom_id){
        var trg = document.forms["recom_sort_chg_form"];
        trg.method="post";
        document.recom_sort_chg_form["fnc"].value    =fnc;
        document.recom_sort_chg_form["id"].value     =recom_id;
        trg.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('recom_chg_form');
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

                const id            = cells[0].textContent;
                const name          = cells[1].textContent;
                const category_name = cells[2].textContent;
                const disp_flag     = cells[5].textContent.trim();
                const user_name     = cells[6].textContent;

                // フォームの対応するフィールドにデータを設定
                document.querySelector('input[name="id"]').value            = id;
                document.querySelector('input[name="name"]').value          = name;
                document.querySelector('input[name="category_name"]').value = category_name;
                document.querySelector('input[name="user_name"]').value     = user_name;

                const selectDispflag = document.querySelector('select[name="disp_flag"]');
                if (disp_flag === '非表示')         selectDispflag.value = '0';
                else if (disp_flag === '表示')      selectDispflag.value = '1';
                else  selectDispflag.value = ''; // その他の場合、空の値にする
                
                        
            });
        });
    });
</script>