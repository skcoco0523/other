<div class="py-3">
    <div class="card">
        <div class="card-header">{{ __('Social Login') }}</div>

        <div class="card-body">
            <?//Lineログイン?>

            <div class="login-container">
                <div class="line-login-container">
                    <a href="{{ route('linelogin') }}" class="login-button">
                        <img src="{{ asset('img/line/btn_login_base.png') }}" class="social-login-button-img" loading="eager">
                        <div class="overlay"></div>
                    </a>
                </div>
                {{--
                <div class="google-login-container py-3">
                    <a href="" class="login-button">
                        <img src="{{ asset('img/google/btn_login_base.png') }}" class="social-login-button-img" loading="eager">
                        <div class="overlay"></div>
                    </a>
                </div>
                --}}
            </div>
        </div>
    </div>
</div>
