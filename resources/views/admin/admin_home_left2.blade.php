<div class="menu_section2">
    @if(isset($method))
        {{---------------- 音楽メニュー ------------------}}
        @if($tab == "music")
            @if($method == "search")       @include('admin.admin_music_search_left') @endif
        @endif

        {{---------------- アルバムメニュー ------------------}}
        @if($tab == "album")
        @if($method == "search")       @include('admin.admin_album_search_left') @endif
        @if($method == "chgdetail")       @include('admin.admin_album_search_left') @endif
        @endif

        {{---------------- アーティストメニュー ------------------}}
        @if($tab == "artist")
            @if($method == "search")       @include('admin.admin_artist_search_left') @endif
        @endif

        {{---------------- プレイリストメニュー ------------------}}
        @if($tab == "playlist")
            @if($method == "search")       @include('admin.admin_playlist_search_left') @endif
            @if($method == "chgdetail")    @include('admin.admin_playlist_search_left') @endif
        @endif
        
        {{---------------- おすすめメニュー ------------------}}
        @if($tab == "recommend")
            @if($method == "search")       @include('admin.admin_recommend_search_left') @endif
            @if($method == "chgdetail")    @include('admin.admin_recommend_search_left') @endif
        @endif
        
        {{---------------- ユーザーメニュー ------------------}}
        @if($tab == "user")
            @if($method == "search")       @include('admin.admin_user_search_left') @endif
            @if($method == "repuest")      @include('admin.admin_request_search_left') @endif
        @endif     

        {{---------------- 広告メニュー ------------------}}
        @if($tab == "adv")
            @if($method == "search")       @include('admin.admin_adv_search_left') @endif
        @endif

        
    @endif
        
</div>