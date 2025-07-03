
{{-- IoTデバイス登録処理 --}}
<form id="mus_reg_form" method="POST" action="{{ route('admin-iotdevice-reg') }}">
    @csrf
    <div class="row g-3 align-items-stretch mb-3">
        <div class="col-6 col-md-4">
            <label for="inputname" class="form-label">MACアドレス</label>
            <input type="text" name="mac_addr" class="form-control" placeholder="mac_addr" value="{{$input['mac_addr'] ?? ''}}">
        </div>
        <div class="col-6 col-md-4">
            <label for="inputbirth" class="form-label">デバイスタイプ</label>
            <select name="device_type" class="form-control">
                <option value=""  {{ ($input['device_type'] ?? '') == ''  ? 'selected' : '' }}></option>
                @foreach (config('common.device_type') as $key => $value)
                    <option value="{{ $value }}" {{ ($input['device_type'] ?? '') == (string)$value ? 'selected' : '' }}>{{ $key }}</option>
                @endforeach
                
            </select>
        </div>
        <div class="col-6 col-md-4">
            <label for="inputbirth" class="form-label">var</label>
            <select name="device_ver" class="form-control">
                @for ($i=1; $i<=10; $i++ )
                    <option value="{{ $i }}" {{ ($input['device_ver'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
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

{{--デバイス登録履歴--}}
@if(isset($iotdevice_list))
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
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    
});


</script>
