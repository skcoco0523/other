@if(isset($tab2))
    {{---------------- デバイスメニュー ------------------}}
    @if($tab1 == "iotdevice")
        @if($tab2 == "reg")          @include('admin.admin_iotdevice_reg') @endif
        @if($tab2 == "search")       @include('admin.admin_iotdevice_search') @endif
    @endif

    {{---------------- リモコンメニュー ------------------}}
    @if($tab1 == "virtualremote-blade")
        @if($tab2 == "reg")             @include('admin.admin_virtualremoteblade_reg') @endif
        @if($tab2 == "search")          @include('admin.admin_virtualremoteblade_search') @endif
    @endif

    {{---------------- ユーザーメニュー ------------------}}
    @if($tab1 == "user")
        @if($tab2 == "search")          @include('admin.admin_user_search') @endif
        @if($tab2 == "repuest")         @include('admin.admin_request_search') @endif
    @endif
    
    {{---------------- 広告メニュー ------------------}}
    @if($tab1 == "adv")
        @if($tab2 == "reg")             @include('admin.admin_adv_reg') @endif
        @if($tab2 == "search")          @include('admin.admin_adv_search') @endif
    @endif

    {{---------------- 通知メニュー ------------------}}
    @if($tab1 == "notification")
        @if($tab2 == "search")          @include('admin.admin_notification') @endif
    @endif


    {{---------------- その他メニュー ------------------}}
    @if($tab1 == "another")
        @if($tab2 == "memo-search")     @include('admin.admin_memo_search') @endif
    @endif  

@endif