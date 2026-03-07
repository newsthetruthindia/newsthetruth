<!doctype html>
<html class="no-js" data-theme="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Register - {{ env('MAIL_FROM_NAME') }}</title>
    <meta name="author" content="{{ env('MAIL_FROM_NAME') }}">
    <meta name="description" content="Register">
    <meta name="keywords" content="Register">
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
                <li>Register</li>
            </ul>
        </div>
    </div>

    <section class="space d-flex align-items-center min-vh-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-5 col-md-7">
                    <div class="login-panel shadow-lg rounded-4 p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">{{ __('Register') }}</h4>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- First Name -->
                            <div class="form-floating mb-3">
                                <input id="firstname" type="text" 
                                       class="form-control @error('firstname') is-invalid @enderror" 
                                       name="firstname" value="{{ old('firstname') }}" 
                                       placeholder="First Name" required autocomplete="given-name" autofocus>
                                <label for="firstname">{{ __('First Name') }}</label>
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="form-floating mb-3">
                                <input id="lastname" type="text" 
                                       class="form-control @error('lastname') is-invalid @enderror" 
                                       name="lastname" value="{{ old('lastname') }}" 
                                       placeholder="Last Name" required autocomplete="family-name">
                                <label for="lastname">{{ __('Last Name') }}</label>
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" 
                                       placeholder="Email" required autocomplete="email">
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
                                    {{ __('Register') }} <i class="fa fa-user-plus"></i>
                                </button>
                            </div>

                            <!-- Already have an account -->
                            <div class="text-center mt-3">
                                <small>Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></small>
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
