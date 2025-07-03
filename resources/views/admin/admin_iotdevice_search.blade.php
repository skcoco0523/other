
{{-- IoTデバイス情報更新処理 --}}
<form id="iotdevices_chg_form" method="POST" action="{{ route('admin-iotdevice-chg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        {{--検索条件--}}
        <input type="hidden" name="search_addr" value="{{$input['search_addr'] ?? ''}}">
        <input type="hidden" name="search_owner_id" value="{{$input['search_owner_id'] ?? ''}}">
        <input type="hidden" name="search_type" value="{{$input['search_type'] ?? ''}}">
        <input type="hidden" name="search_ver" value="{{$input['search_ver'] ?? ''}}">
        <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
        {{--対象データ--}}
        <input type="hidden" name="id" value="{{$select->id ?? ''}}">
        
        <div class="col-6 col-md-3">
            <label for="inputname" class="form-label">MACアドレス</label>
            <input type="text" name="mac_addr" class="form-control" placeholder="mac_addr" value="{{ $select->mac_addr ?? ($input['mac_addr'] ?? '') }}">
        </div>

        <div class="col-6 col-md-3">
            ・デバイスタイプ
            <select name="device_type" class="form-control">
                <option value=""  {{ ($input['device_type'] ?? '') == ''  ? 'selected' : '' }}></option>
                @foreach (config('common.device_type') as $key => $value)
                    <option value="{{ $value }}" {{ ($input['device_type'] ?? '') == (string)$value ? 'selected' : '' }}>{{ $key }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label for="inputbirth" class="form-label">var</label>
            <select name="device_ver" class="form-control">
                @for ($i=1; $i<=10; $i++ )
                    <option value="{{ $i }}" {{ ($input['device_ver'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        
        <div class="col-6 col-md-3">
            <label for="inputname" class="form-label">デバイス名</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{ $select->name ?? ($input['name'] ?? '') }}">
        </div>

    </div>

    <div class="text-end mb-3">
        <input type="submit" value="更新" class="btn btn-primary">
    </div>

</form>

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--IoTデバイス一覧--}}
@if(isset($iotdevice_list))
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $page_prm = $input ?? '';
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $iotdevice_list,'page_prm' => $page_prm,])
    <div style="overflow-x: auto;">
        <table class="table table-striped table-hover table-bordered fs-6 ">
            <thead>
            <tr>
                <th scope="col" class="fw-light">#</th>
                <th scope="col" class="fw-light">MACアドレス</th>
                <th scope="col" class="fw-light">タイプ</th>
                <th scope="col" class="fw-light">バージョン</th>
                <th colspan="2" class="fw-light">所有者</th>
                <th scope="col" class="fw-light">デバイス名</th>
                <th scope="col" class="fw-light">データ登録日</th>
                <th scope="col" class="fw-light">データ更新日</th>
                <th scope="col" class="fw-light"></th>
                <th scope="col" class="fw-light"></th>
            </tr>
            </thead>
            @foreach($iotdevice_list as $iotdevice)
                <tr>
                    <td class="fw-light">{{$iotdevice->id}}</td>
                    <td class="fw-light">{{$iotdevice->mac_addr}}</td>
                    <td class="fw-light">
                        @php
                            $type_name = '未登録の種別'; // デフォルト値
                            foreach (config('common.device_type') as $key => $value) {
                                if ($value === $iotdevice->type) { $type_name = $key;}
                            }
                        @endphp
                        {{ $type_name }}
                    </td>
                    <td class="fw-light">{{$iotdevice->ver}}</td>
                    <td class="fw-light">{{$iotdevice->uid}}</td>
                    <td class="fw-light">{{$iotdevice->uname}}</td>
                    <td class="fw-light">{{$iotdevice->name}}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $iotdevice->created_at) !!}</td>
                    <td class="fw-light">{!! str_replace(' ', '<br>', $iotdevice->updated_at) !!}</td>

                    <td class="fw-light">
                        <input type="button" value="編集" class="btn btn-primary edit-btn">
                    </td>
                    <td class="fw-light">
                        <form method="POST" action="{{ route('admin-iotdevice-del') }}">
                            @csrf
                            {{--検索条件--}}
                            <input type="hidden" name="search_addr" value="{{$input['search_addr'] ?? ''}}">
                            <input type="hidden" name="search_owner_id" value="{{$input['search_owner_id'] ?? ''}}">
                            <input type="hidden" name="search_type" value="{{$input['search_type'] ?? ''}}">
                            <input type="hidden" name="search_ver" value="{{$input['search_ver'] ?? ''}}">
                            <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
                            {{--対象データ--}}
                            <input type="hidden" name="id" value="{{$iotdevice->id}}">
                            <input type="submit" value="削除" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $iotdevice_list,'page_prm' => $page_prm,])
@endif

<script>

document.addEventListener('DOMContentLoaded', function () {

    var affiliateLinkInput = document.getElementById('affiliate-link');
    var affiliatePreview = document.getElementById('affiliate-preview');
    const deviceTypeMap = @json(config('common.device_type'));

    const form = document.getElementById('iotdevices_chg_form');
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
            const mac_addr      = cells[1].textContent;
            const device_type   = cells[2].textContent.trim();
            const device_type_value = deviceTypeMap[device_type] ?? '';
            const device_ver    = cells[3].textContent.trim();
            const name          = cells[6].textContent;
            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value            = id;
            document.querySelector('input[name="mac_addr"]').value      = mac_addr;
            document.querySelector('select[name="device_ver"]').value   = device_ver;
            document.querySelector('select[name="device_type"]').value  = device_type_value;
            document.querySelector('input[name="name"]').value          = name;

        });
    });
    
    
});
</script>