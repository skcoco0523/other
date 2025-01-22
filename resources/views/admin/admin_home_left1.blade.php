<div class="menu_section">
    @if(isset($tab))
        @if($tab == "music")
            曲メニュー
            <li><a href="{{ route('admin-music-reg') }}">               新規登録</a></li>
            <li><a href="{{ route('admin-music-search') }}">            検索/変更/削除</a></li>
            <li><a href="{{ route('admin-custom-category-setting') }}"> カテゴリー設定</a></li>
        @endif

        @if($tab == "album")
            アルバムメニュー
            <li><a href="{{ route('admin-album-reg') }}">               新規登録</a></li>
            <li><a href="{{ route('admin-album-search') }}">            検索/変更/削除</a></li>
        @endif

        @if($tab == "artist")
            アーティストメニュー
            <li><a href="{{ route('admin-artist-reg') }}">              新規登録</a></li>
            <li><a href="{{ route('admin-artist-search') }}">           検索/変更/削除</a></li>
        @endif

        @if($tab == "playlist")
            プレイリストメニュー
            <li><a href="{{ route('admin-playlist-reg') }}">            新規登録</a></li>
            <li><a href="{{ route('admin-playlist-search') }}">         検索/変更/削除</a></li>
        @endif
        
        @if($tab == "recommend")
            おすすめメニュー
            <li><a href="{{ route('admin-recommend-reg') }}">           新規登録</a></li>
            <li><a href="{{ route('admin-recommend-search') }}">        検索/変更/削除</a></li>
        @endif
        
        @if($tab == "user")
            ユーザーメニュー
            <li><a href="{{ route('admin-user-search') }}">             ユーザー</a></li>
            <li><a href="{{ route('admin-request-search') }}">          要望・問い合わせ</a></li>
        @endif
        @if($tab == "adv")
            広告メニュー
            <li><a href="{{ route('admin-adv-reg') }}">             新規登録</a></li>
            <li><a href="{{ route('admin-adv-search') }}">          検索/変更/削除</a></li>
        @endif
        
        @if($tab == "notification")
            通知メニュー
            <li><a href="">               メール通知(未開発)</a></li>
            <li><a href="">               プッシュ通知(未開発)</a></li>
            <li><a href="">               通知履歴(未開発)</a></li>
        @endif

        @if($tab == "another")
            その他メニュー
        @endif    
    @endif

</div>
