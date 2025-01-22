@if(isset($method))
    {{---------------- 音楽メニュー ------------------}}
    @if($tab == "music")
        @if($method == "reg")          @include('admin.admin_music_reg') @endif
        @if($method == "search")       @include('admin.admin_music_search') @endif
        @if($method == "category")     @include('admin.admin_category_setting') @endif
    @endif

    {{---------------- アルバムメニュー ------------------}}
    @if($tab == "album")
        @if($method == "reg")          @include('admin.admin_album_reg') @endif
        @if($method == "search")       @include('admin.admin_album_search') @endif
        @if($method == "chgdetail")    @include('admin.admin_album_search') @endif
    @endif

    {{---------------- アーティストメニュー ------------------}}
    @if($tab == "artist")
        @if($method == "reg")          @include('admin.admin_artist_reg') @endif
        @if($method == "search")       @include('admin.admin_artist_search') @endif
    @endif

    {{---------------- プレイリストメニュー ------------------}}
    @if($tab == "playlist")
        @if($method == "reg")          @include('admin.admin_playlist_reg') @endif
        @if($method == "search")       @include('admin.admin_playlist_search') @endif
        @if($method == "chgdetail")    @include('admin.admin_playlist_search') @endif
    @endif
    
    {{---------------- おすすめメニュー ------------------}}
    @if($tab == "recommend")
        @if($method == "reg")          @include('admin.admin_recommend_reg') @endif
        @if($method == "search")       @include('admin.admin_recommend_search') @endif
        @if($method == "chgdetail")    @include('admin.admin_recommend_search') @endif
    @endif
    
    {{---------------- ユーザーメニュー ------------------}}
    @if($tab == "user")
        @if($method == "search")          @include('admin.admin_user_search') @endif
        @if($method == "repuest")         @include('admin.admin_request_search') @endif
    @endif
    
    {{---------------- 広告メニュー ------------------}}
    @if($tab == "adv")
        @if($method == "reg")       @include('admin.admin_adv_reg') @endif
        @if($method == "search")       @include('admin.admin_adv_search') @endif
    @endif

@endif