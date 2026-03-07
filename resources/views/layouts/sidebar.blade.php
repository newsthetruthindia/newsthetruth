<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="/">
                @if( !empty( \App\Http\Controllers\SiteSettingsController::check('site_logo') ) )
                    @php
                        $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('site_logo')->description);
                    @endphp
                    @if( !empty( $logo->url ) )
                        <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 120px;"/>
                    @endif
                @else
                    <img class="main-logo" src="{{ asset('public/img/logo/logo.jpg') }}" alt="" style="width: 120px;"/>
                @endif
            </a>
            @if( !empty( \App\Http\Controllers\SiteSettingsController::check('site_logo') ) )              
                @php
                    $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('site_logo')->description);
                @endphp
                @if( !empty( $logo->url ) )
                    <strong><img src="{{ url($logo->url) }}" alt="" /></strong>
                @endif
            @else
                <strong><img src="{{ asset('public/img/logo/logosn.png') }}" alt="" /></strong>
            @endif
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                    <li class="active">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span class="mini-click-non">Dashboard</span>
                        </a>
                    </li>
                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_post ) )
                        <li class="active">
                            <a href="{{ route('quick-post') }}">
                                <i class="fa fa-pencil-square"></i>
                                <span class="mini-click-non">Quick Story</span>
                            </a>
                        </li>
                    @endif
                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_user || Auth::user()->details->role->view_user ) )
                        <li class="">
                            <a class="has-arrow" href="{{ route('home') }}">
                               <i class="icon nalika-home icon-wrap"></i>
                               <span class="mini-click-non">Users</span>
                            </a>
                            <ul class="submenu-angle" aria-expanded="true">
                                @if( Auth::user()->details->role->view_user )
                                    <li><a title="User Lists" href="{{ route('user-list') }}"><span class="mini-sub-pro">All Users</span></a></li>
                                    <li><a title="User Lists" href="{{ route('subscriber-list') }}"><span class="mini-sub-pro">All Subscribers</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->create_user )
                                    <li><a title="User Lists" href="{{ route('user-add') }}"><span class="mini-sub-pro">Add Users</span></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_post || Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post || Auth::user()->details->role->update_post_seo || Auth::user()->details->role->view_page_list || Auth::user()->details->role->edit_page || Auth::user()->details->role->delete_page) )
                        <li>
                            <a class="has-arrow" href="{{ route( 'posts' ) }}" aria-expanded="false"><i class="icon nalika-diamond icon-wrap"></i> <span class="mini-click-non">Posts & Pages</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                @if( Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post || Auth::user()->details->role->update_post_seo )
                                    <li><a title="Google Map" href="{{ route( 'posts' ) }}"><span class="mini-sub-pro">All Posts</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->create_post )
                                    <li><a title="Data Maps" href="{{ route( 'add-post' ) }}"><span class="mini-sub-pro">Add New Post</span></a></li>
                                    <li><a title="Journalism" href="{{ route( 'lists-citizen-journalism' ) }}"><span class="mini-sub-pro">Citizen Journalism</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post || Auth::user()->details->role->update_post_seo )
                                    <li><a title="Google Map" href="{{ route( 'justins' ) }}"><span class="mini-sub-pro">Just In</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->view_page_list || Auth::user()->details->role->edit_page || Auth::user()->details->role->delete_page )
                                    <li><a title="Google Map" href="{{ route( 'pages' ) }}"><span class="mini-sub-pro">All Pages</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->create_page )
                                    <li><a title="Data Maps" href="{{ route( 'add-page' ) }}"><span class="mini-sub-pro">Add New Page</span></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li>
                        <a class="has-arrow" href="{{ route('medias') }}" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">Medias</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="File Manager" href="{{ route('medias') }}"><span class="mini-sub-pro">Lists</span></a></li>
                            <li><a title="Blog" href="{{ route('medias') }}"><span class="mini-sub-pro">Add New</span></a></li>
                        </ul>
                    </li>
                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_category || Auth::user()->details->role->edit_category|| Auth::user()->details->role->delete_category|| Auth::user()->details->role->edit_tag|| Auth::user()->details->role->create_tag ) )
                        <li>
                            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-table icon-wrap"></i> <span class="mini-click-non">Categories</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                @if( Auth::user()->details->role->edit_category|| Auth::user()->details->role->delete_category )
                                    <li><a title="Peity Charts" href="{{ route('categories')}}"><span class="mini-sub-pro">All Categories</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->create_category )
                                    <li><a title="Data Table" href="{{ route('add-category') }}"><span class="mini-sub-pro">Add Category</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->edit_tag || Auth::user()->details->role->delete_tag )
                                    <li><a title="Data Table" href="{{ route('tags') }}"><span class="mini-sub-pro">All tags</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->create_tag )
                                <li><a title="Data Table" href="{{ route('add-tag') }}"><span class="mini-sub-pro">Add Tag</span></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->view_settings || Auth::user()->details->role->update_settings ) )
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-forms icon-wrap"></i> <span class="mini-click-non">Settings</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Basic Form Elements" href="{{ route('settings-roles') }}"><span class="mini-sub-pro">Roles</span></a></li>
                                @if( Auth::user()->details->role->create_tag )
                                    <li><a title="Basic Form Elements" href="{{ route('settings-site') }}"><span class="mini-sub-pro">Site Settings</span></a></li>
                                @endif
                                @if( Auth::user()->details->role->create_tag )
                                    <li><a title="Basic Form Elements" href="{{ route('menus') }}"><span class="mini-sub-pro">Menu Settings</span></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </nav>
</div>
  <!-- Start Welcome area -->
<div class="all-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="logo-pro">
                    <a href="/">
                        @if( !empty( \App\Http\Controllers\SiteSettingsController::check('site_logo') ) )
                            @php
                                $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('site_logo')->description);
                            @endphp
                            @if( !empty( $logo->url ) )
                                <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 120px;"/>
                            @endif
                        @else
                            <img class="main-logo" src="{{ asset('public/img/logo/logo.jpg') }}" alt="" style="width: 120px;"/>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="header-advance-area">
        <div class="header-top-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper">
                            <div class="row">
                                <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                                    <div class="menu-switcher-pro">
                                        <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                                                <i class="icon nalika-menu-task"></i>
                                            </button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                    <div class="header-top-menu tabl-d-n">
                                        <div class="breadcome-heading">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <div class="header-right-info">
                                        <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                            <!-- <li class="nav-item dropdown">
                                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-mail nalika-chat-pro" aria-hidden="true"></i><span class="indicator-ms"></span></a>
                                                <div role="menu" class="author-message-top dropdown-menu animated zoomIn">
                                                    <div class="message-single-top">
                                                        <h1>Message</h1>
                                                    </div>
                                                    <ul class="message-menu">
                                                        <li>
                                                            <a href="#">
                                                                <div class="message-img">
                                                                    <img src="{{ asset('public/img/contact/1.jpg') }}" alt="">
                                                                </div>
                                                                <div class="message-content">
                                                                    <span class="message-date">16 Sept</span>
                                                                    <h2>Advanda Cro</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <div class="message-img">
                                                                    <img src="{{ asset('public/img/contact/4.jpg') }}" alt="">
                                                                </div>
                                                                <div class="message-content">
                                                                    <span class="message-date">16 Sept</span>
                                                                    <h2>Sulaiman din</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <div class="message-img">
                                                                    <img src="{{ asset('public/img/contact/3.jpg') }}" alt="">
                                                                </div>
                                                                <div class="message-content">
                                                                    <span class="message-date">16 Sept</span>
                                                                    <h2>Victor Jara</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <div class="message-img">
                                                                    <img src="{{ asset('public/img/contact/2.jpg') }}" alt="">
                                                                </div>
                                                                <div class="message-content">
                                                                    <span class="message-date">16 Sept</span>
                                                                    <h2>Victor Jara</h2>
                                                                    <p>Please done this project as soon possible.</p>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="message-view">
                                                        <a href="#">View All Messages</a>
                                                    </div>
                                                </div>
                                            </li> -->
                                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-alarm" aria-hidden="true"></i><span class="indicator-nt"></span></a>
                                                <div role="menu" class="notification-author dropdown-menu animated zoomIn">
                                                    <div class="notification-single-top">
                                                        <h1>Notifications</h1>
                                                    </div>
                                                    <ul class="notification-menu" id="notification_menu"></ul>
                                                    <div class="notification-view">
                                                        <a href="{{ route('notifications')}}">View All Notification</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                                        <i class="icon nalika-user nalika-user-rounded header-riht-inf" aria-hidden="true"></i>
                                                        <span class="admin-name">Advanda Cro</span>
                                                        <i class="icon nalika-down-arrow nalika-angle-dw nalika-icon"></i>
                                                    </a>
                                                <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                    <!-- <li><a href="register.html"><span class="icon nalika-home author-log-ic"></span> Register</a>
                                                    </li> -->
                                                    <li><a href="{{ route('user-profile-edit', ['id'=> Auth::user()->id]) }}"><span class="icon nalika-user author-log-ic"></span> My Profile</a>
                                                    </li>
                                                   <!--  <li><a href="lock.html"><span class="icon nalika-diamond author-log-ic"></span> Lock</a>
                                                    </li> -->
                                                    <li><a href="{{ route('user-settings', ['id'=> Auth::user()->id]) }}"><span class="icon nalika-settings author-log-ic"></span>My Settings</a>
                                                    </li>
                                                    <li><a href="{{ route('change-password', ['id'=> Auth::user()->id]) }}"><span class="icon nalika-settings author-log-ic"></span>Change Password</a>
                                                    </li>
                                                    <li><a href="{{ route('home') }}"><span class="icon nalika-settings author-log-ic"></span>Delete Account</a>
                                                    </li>
                                                    <li><a href="{{ route('userlogout') }}"><span class="icon nalika-unlocked author-log-ic"></span> {{ __('Logout') }}</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu start -->
        <div class="mobile-menu-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="mobile-menu">
                            <nav id="dropdown">
                                <ul class="mobile-menu-nav">
                                    <li>
                                        <a data-toggle="collapse" href="{{ route('dashboard') }}">Dashboard
                                            <span class="admin-project-icon nalika-icon nalika-down-arrow"></span>
                                        </a>
                                    </li>
                                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_post || Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post ) )
                                        @if( Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post || Auth::user()->details->role->update_post_seo )
                                            <li><a title="Google Map" href="{{ route( 'justins' ) }}"><span class="mini-sub-pro">Just In</span></a></li>
                                            <li><a href="{{ route('add-justin') }}"><span class="mini-sub-pro">Add Just In</span></a></li>
                                            <li><a href="{{ route('quick-post') }}"><span class="mini-click-non">Quick Story</span></a></li>
                                        @endif
                                    @endif
                                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_user || Auth::user()->details->role->view_user ) )
                                        <li>
                                            <a data-toggle="collapse" data-target="#demo" href="#">User<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="demo" class="collapse dropdown-header-top">
                                                @if( Auth::user()->details->role->view_user )
                                                    <li><a title="User Lists" href="{{ route('user-list') }}"><span class="mini-sub-pro">All Users</span></a></li>
                                                    <li><a title="User Lists" href="{{ route('subscriber-list') }}"><span class="mini-sub-pro">All Subscribers</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->create_user )
                                                    <li><a title="User Lists" href="{{ route('user-add') }}"><span class="mini-sub-pro">Add Users</span></a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_post || Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post || Auth::user()->details->role->update_post_seo || Auth::user()->details->role->view_page_list || Auth::user()->details->role->edit_page || Auth::user()->details->role->delete_page) )
                                        <li>
                                            <a data-toggle="collapse" data-target="#others" href="#">Posts & Pages<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="others" class="collapse dropdown-header-top">
                                                @if( Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post || Auth::user()->details->role->update_post_seo )
                                                    <li><a title="Google Map" href="{{ route( 'posts' ) }}"><span class="mini-sub-pro">All Posts</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->create_post )
                                                    <li><a title="Data Maps" href="{{ route( 'add-post' ) }}"><span class="mini-sub-pro">Add New Post</span></a></li>
                                                    <li><a title="Journalism" href="{{ route( 'lists-citizen-journalism' ) }}"><span class="mini-sub-pro">Citizen Journalism</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->view_post_list || Auth::user()->details->role->edit_post || Auth::user()->details->role->delete_post || Auth::user()->details->role->review_post || Auth::user()->details->role->publish_post || Auth::user()->details->role->update_post_seo )
                                                    <li><a title="Google Map" href="{{ route( 'justins' ) }}"><span class="mini-sub-pro">Just In</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->view_page_list || Auth::user()->details->role->edit_page || Auth::user()->details->role->delete_page )
                                                    <li><a title="Google Map" href="{{ route( 'pages' ) }}"><span class="mini-sub-pro">All Pages</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->create_page )
                                                    <li><a title="Data Maps" href="{{ route( 'add-page' ) }}"><span class="mini-sub-pro">Add New Page</span></a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->create_category || Auth::user()->details->role->edit_category|| Auth::user()->details->role->delete_category|| Auth::user()->details->role->edit_tag|| Auth::user()->details->role->create_tag ) )
                                        <li>
                                            <a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">Categories<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                                @if( Auth::user()->details->role->edit_category|| Auth::user()->details->role->delete_category )
                                                    <li><a title="Peity Charts" href="{{ route('categories')}}"><span class="mini-sub-pro">All Categories</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->create_category )
                                                    <li><a title="Data Table" href="{{ route('add-category') }}"><span class="mini-sub-pro">Add Category</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->edit_tag || Auth::user()->details->role->delete_tag )
                                                    <li><a title="Data Table" href="{{ route('tags') }}"><span class="mini-sub-pro">All tags</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->create_tag )
                                                <li><a title="Data Table" href="{{ route('add-tag') }}"><span class="mini-sub-pro">Add Tag</span></a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if( !empty( Auth::user()->details->role ) && ( Auth::user()->details->role->view_settings || Auth::user()->details->role->update_settings ) )
                                        <li>
                                            <a data-toggle="collapse" data-target="#Chartsmob" href="#">Settings<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="Chartsmob" class="collapse dropdown-header-top">
                                                <li><a title="Basic Form Elements" href="{{ route('settings-roles') }}"><span class="mini-sub-pro">Roles</span></a></li>
                                                @if( Auth::user()->details->role->create_tag )
                                                    <li><a title="Basic Form Elements" href="{{ route('settings-site') }}"><span class="mini-sub-pro">Site Settings</span></a></li>
                                                @endif
                                                @if( Auth::user()->details->role->create_tag )
                                                    <li><a title="Basic Form Elements" href="{{ route('menus') }}"><span class="mini-sub-pro">Menu Settings</span></a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu end -->
        