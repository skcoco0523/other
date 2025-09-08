@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<a href="{{ }}">
    <img src="{{   }}" class="icon-80p" alt="pic">
</a>
<div class="text-center">
    <p class="detail-title">{{ $iotdevice->device_name }}</p>
    <p class="detail-txt">
            所有者：{{ $iotdevice->name }}
    </p>   
    <p>{{ $album->release_date }}：{{$album->mus_cnt }}曲</p>
</div>


@include('layouts.list_table', ['non_menu_table' => $iotdevice_list, 'table' => 'iotdevice'])
{{--ﾊﾟﾗﾒｰﾀ--}}
@php
    $additionalParams = ['table' => 'iotdevice' ,];
@endphp
{{--ﾍﾟｰｼﾞｬｰ--}}
@include('layouts.pagination', ['paginator' => $iotdevice_list,'additionalParams' => $additionalParams,])


<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection
