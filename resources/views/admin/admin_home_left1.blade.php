<div class="menu_section">
    @if(isset($tab1))
        @if($tab1 == "iotdevice")
            デバイスメニュー
            <li><a href="{{ route('admin-iotdevice-reg') }}">               新規登録</a></li>
            <li><a href="{{ route('admin-iotdevice-search') }}">            検索/変更/削除</a></li>
        @endif

        @if($tab1 == "virtualremote-blade")
            リモコンメニュー
            <br>
            <br>
            デザイン
            <li><a href="{{ route('admin-virtualremote-blade-reg') }}">               新規登録</a></li>
            <li><a href="{{ route('admin-virtualremote-blade-search') }}">            検索/変更/削除</a></li>
            <br>
            ユーザー別
            <li><a href="{{ route('admin-virtualremote-blade-reg') }}">               新規登録</a></li>
            <li><a href="{{ route('admin-virtualremote-blade-search') }}">            検索/変更/削除</a></li>
        @endif
        
        @if($tab1 == "user")
            ユーザーメニュー
            <li><a href="{{ route('admin-user-search') }}">             ユーザー</a></li>
            <li><a href="{{ route('admin-request-search') }}">          要望・問い合わせ</a></li>
        @endif
        @if($tab1 == "adv")
            広告メニュー
            <li><a href="{{ route('admin-adv-reg') }}">             新規登録</a></li>
            <li><a href="{{ route('admin-adv-search') }}">          検索/変更/削除</a></li>
        @endif
        
        @if($tab1 == "notification")
            通知メニュー
            <li><a href="">               メール通知(未開発)</a></li>
            <li><a href="">               プッシュ通知(未開発)</a></li>
            <li><a href="">               通知履歴(未開発)</a></li>
        @endif

        @if($tab1 == "another")
            その他メニュー
            <li><a href="{{ route('admin-memo-search') }}">             メモ</a></li>

        @endif    
    @endif

</div>
