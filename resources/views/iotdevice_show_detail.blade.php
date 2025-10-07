@extends('layouts.app')

@section('content')

<div class="text-center">
    <p class="detail-title">{{ $iotdevice->name }}</p>
    <p class="detail-txt">
            所有者：{{ $iotdevice->uname }}
    </p>   
</div>


<?//広告モーダル?>   
@include('layouts.adv_popup')

@endsection
