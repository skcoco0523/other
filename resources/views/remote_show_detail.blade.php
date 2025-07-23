@extends('layouts.app')

@section('content')
    <div class="container py-4">

        @include($virtual_remote->blade_path)

    </div>

    <?//広告モーダル?>   
    @include('layouts.adv_popup')
    

@endsection
