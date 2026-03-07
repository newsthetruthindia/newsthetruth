@php
    $smenu_id = \App\Http\Controllers\SiteSettingsController::check('secondary_menu');
    $smenu = App\Models\Menu::where('id', $smenu_id->description)->first();
@endphp

<header class="th-header header-layout2 dark-theme">

    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row justify-content-center justify-content-md-between align-items-center gy-2">

                <!-- Left -->
                <div class="col-auto d-none d-md-inline-block">
                    <div class="header-icon">
                        <a href="#" class="simple-icon sideMenuToggler">
                            <i class="far fa-bars"></i>
                        </a>
                        <button type="button" class="simple-icon">
                            <i class="far fa-user"></i>
                        </button>
                    </div>

                    <div class="header-links">
                        <ul>
                            <li>
                                <i class="fal fa-calendar-days"></i>
                                <a href="#">{{ date('d F, Y') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Center Logo -->
                <div class="col-auto d-none d-lg-inline-block">
                    <div class="header-logo">
                        <a href="{{ route('v1.home') }}">
                            <img class="light-img" src="{{ asset('public/v1/img/image_logo.jpg') }}" alt="Logo">
                            <img class="dark-img" src="{{ asset('public/v1/img/image_logo.jpg') }}" alt="Logo">
                        </a>
                    </div>
                </div>

                <!-- Right -->
                <div class="col-auto text-center text-md-end">
                    <div class="header-icon">
                        <div class="theme-switcher">
                            <button>
                                <span class="dark"><i class="fas fa-moon"></i></span>
                                <span class="light"><i class="fas fa-sun-bright"></i></span>
                            </button>
                        </div>
                    </div>

                    <div class="header-links">
                        <ul>
                            <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('terms') }}">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Sticky Menu -->
    <div class="sticky-wrapper">
        <div class="menu-area">
            <div class="container">
                <div class="row align-items-center justify-content-between">

                    <!-- Mobile Logo -->
                    <div class="col-auto d-lg-none d-block">
                        <div class="header-logo">
                            <a href="{{ route('v1.home') }}">
                                <img src="{{ asset('public/v1/img/image_logo.jpg') }}" alt="Logo">
                            </a>
                        </div>
                    </div>

                    <!-- Main Menu -->
                    <div class="col-auto">
                        <nav class="main-menu d-none d-lg-inline-block">
                            <ul>
                                @if(!empty($smenu->items))
                                    @foreach($smenu->items as $item)
                                        <li>
                                            <a href="{{ route('public.page', ['x' => $item->only_slug]) }}">
                                                {{ $item->display_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </nav>
                    </div>

                    <!-- Right Buttons -->
                    <div class="col-auto">
                        <div class="header-button">
                            <button type="button" class="simple-icon searchBoxToggler">
                                <i class="far fa-search"></i>
                            </button>

                            <!-- <button type="button" class="simple-icon d-none d-lg-block cartToggler">
                                <i class="far fa-cart-shopping"></i>
                            </button> -->

                            <button type="button" class="th-menu-toggle d-block d-lg-none">
                                <i class="far fa-bars"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</header>
