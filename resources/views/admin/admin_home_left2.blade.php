<div class="menu_section2">
    @if(isset($tab2))
        {{---------------- デバイスメニュー ------------------}}
        @if($tab1 == "iotdevice")
            @if($tab2 == "search")       @include('admin.admin_iotdevice_search_left') @endif
        @endif

        {{---------------- リモコンメニュー ------------------}}
        @if($tab1 == "virtualremote-blade")
            @if($tab2 == "search")
                @include('admin.admin_virtualremoteblade_search_left') 
            @endif
        @endif

        {{---------------- ユーザーメニュー ------------------}}
        @if($tab1 == "user")
            @if($tab2 == "search")       @include('admin.admin_user_search_left') @endif
            @if($tab2 == "repuest")      @include('admin.admin_request_search_left') @endif
        @endif     

        {{---------------- 広告メニュー ------------------}}
        @if($tab1 == "adv")
            @if($tab2 == "search")       @include('admin.admin_adv_search_left') @endif
        @endif

        
    @endif
        
</div>