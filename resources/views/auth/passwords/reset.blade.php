<!doctype html>
<html class="no-js" data-theme="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Reset Password - {{ env('MAIL_FROM_NAME') }}</title>
    <meta name="author" content="{{ env('MAIL_FROM_NAME') }}">
    <meta name="description" content="Reset Password">
    <meta name="keywords" content="Reset Password">
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
                <li><a href="{{ route('v1.home') }}">Home</a></li>
                <li>Reset Password</li>
            </ul>
        </div>
    </div>

    <section class="space d-flex align-items-center min-vh-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-5 col-md-7">
                    <div class="login-panel shadow-lg rounded-4 p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">{{ __('Reset Password') }}</h4>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ $email ?? old('email') }}" 
                                       placeholder="Email Address" required autocomplete="email" autofocus>
                                <label for="email">{{ __('Email Address') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="Password" required autocomplete="new-password">
                                <label for="password">{{ __('Password') }}</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-floating mb-4">
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="th-btn style3">
                                    {{ __('Reset Password') }} <i class="fa fa-lock-open"></i>
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="text-center mt-3">
                                <small>Remembered your password? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.footer_v1')
    @include('layouts.scripts_v1')
</body>
</html>
