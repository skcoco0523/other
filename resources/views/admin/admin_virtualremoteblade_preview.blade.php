
{{-- リモコンデザインプレビュー --}}
@extends('layouts.app')

<?//コンテンツ?>  
@section('content')

@if($virtualremoteblade->views_path)
    <div class="alert alert-warning text-center" role="alert">
        <p class="text-center mb-0">
            <strong class="text-danger fs-4">【プレビュー】</strong>
        </p>
        <p class="text-center mb-0">
            @if($virtualremoteblade->test_flag)
                テスト状態のため、<br>ユーザーが使用することはできません。
            @endif
        </p>
    </div>

    @include($virtualremoteblade->views_path)
    
@else
    <div class="alert alert-warning text-center" role="alert">
        <p class="mb-0">
            <strong class="text-danger">対象のデザインが作成されていません。</strong>
        </p>
        <p class="mb-0">
            `views/smart_remote` 配下に作成してください。
        </p>
    </div>
@endif

@endsection
