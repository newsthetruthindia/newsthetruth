<!doctype html>
<html class="no-js" data-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login - {{env('MAIL_FROM_NAME')}}</title>
    <meta name="author" content="{{env('MAIL_FROM_NAME')}}">
    <meta name="description" content="Login">
    <meta name="keywords" content="Login">
    <meta name="robots" content="INDEX,FOLLOW">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('public/v1/img/favicons/favicon-32x32.png') }}">
    <meta name="theme-color" content="#ffffff">
    @include('layouts.stylesheets_v1')
</head>

<body>
    @include('layouts.header_v1')
    <div class="breadcumb-wrapper">
        <div class="container">
            <ul class="breadcumb-menu">
                <li><a href="{{route('v1.home')}}">Home</a></li>
                <li>Login</li>
            </ul>
        </div>
    </div>
    <section class="space d-flex align-items-center min-vh-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-5 col-md-7">

                    <div class="login-panel shadow-lg rounded-4 p-4 p-md-5">

                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">{{ __('Login') }}</h4>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Email"
                                    required
                                    autocomplete="email"
                                    autofocus>

                                <label for="email">{{ __('Email Address') }}</label>

                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="Password"
                                    required
                                    autocomplete="current-password">

                                <label for="password">{{ __('Password') }}</label>

                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check ps-0">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                <a class="text-decoration-none small"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="th-btn style3">
                                    {{ __('Login') }} <i class="fa fa-lock"></i>
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- <style>
        .login-panel {
            border: none;
        }

        .form-control {
            border-radius: 0.75rem;
        }

        .btn-dark {
            letter-spacing: 0.5px;
        }
    </style> -->

    @include('layouts.footer_v1')
    @include('layouts.scripts_v1')
</body>

</html>