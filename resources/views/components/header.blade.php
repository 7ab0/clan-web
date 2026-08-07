    <!-- Start Header Section -->
    <header class="ak-site_header ak-style1 ak-sticky_header ak-site_header_full_width">
        <div class="header-top">
            <div class="wrapper">
                <div class="header-logo">
                    <a href="{{ route('reservations') }}" class="logo">Reservation</a>
                </div>
                <div class="center-log">
                    <a href="{{ route('index') }}"><img src="{{ asset('assets/img/clan-logo.svg') }}" alt="CLAN Cocina de Autor"></a>
                </div>
                <button class="ak-menu-toggle" id="akMenuToggle" type="button">
                    <svg viewBox="0 0 20 15" width="40px" height="30px" class="ak-menu-icon">
                        <path d="M20,2 L2,2" class="bar-1"></path>
                        <path d="M2,7 L20,7" class="bar-2"></path>
                        <path d="M30,12 L2,12" class="bar-3"></path>
                    </svg>
                </button>

                <ul class="top-main-menu">
                    <li class="top-main-menu-li">
                        <a href="{{ route('index') }}">Home</a>
                        <img class="top-main-menu-img" src="{{ asset('assets/img/fullWM_1.jpg') }}" alt="...">
                    </li>
                    <li class="top-main-menu-li">
                        <a href="{{ route('about') }}">About</a>
                        <img class="top-main-menu-img" src="{{ asset('assets/img/fullWM_about.jpg') }}" alt="...">
                    </li>
                    <li class="top-main-menu-li">
                        <a href="{{ route('menu') }}">Menu</a>
                        <img class="top-main-menu-img menu-img" src="{{ asset('assets/img/fullWM_menu.jpg') }}" alt="...">
                    </li>
                    <li class="top-main-menu-li">
                        <a href="{{ route('experiencias') }}">Experiencias</a>
                        <img class="top-main-menu-img" src="{{ asset('assets/img/fullWM_about.jpg') }}" alt="...">
                    </li>
                    <li class="top-main-menu-li">
                        <a href="{{ route('chef') }}">Chef</a>
                        <img class="top-main-menu-img" src="{{ asset('assets/img/fullWM_chef.jpg') }}" alt="...">
                    </li>
                    <li class="top-main-menu-li">
                        <a href="{{ route('contact') }}">Contact</a>
                        <img class="top-main-menu-img" src="{{ asset('assets/img/fullWM_contact.jpg') }}" alt="...">
                    </li>
                </ul>

            </div>
        </div>
        <div class="nav-bar-border"></div>
        <div class="ak-main_header">
            <div class="container">
                <div class="ak-main_header_in">
                    <div class="ak-main_header_left">
                        <a class="ak-site_branding" href="{{ route('index') }}">
                            <img src="{{ asset('assets/img/clan-logo.svg') }}" alt="CLAN Cocina de Autor">
                        </a>
                    </div>
                    <div class="ak-main_header_right">
                        <div class="ak-nav ak-medium">
                            <ul class="ak-nav_list">
                                <li class="menu-item-has-children">
                                    <a href="{{ route('index') }}">Home</a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('index') }}">Home 1</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('home2') }}">Home 2</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('home3') }}">Home 3</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route('about') }}">About</a>
                                </li>
                                <li>
                                    <a href="{{ route('menu') }}">Menu</a>
                                </li>
                                <li>
                                    <a href="{{ route('experiencias') }}">Experiencias</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="{{ route('chef') }}">Chef</a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('chef') }}">Chef</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('meetTheChef') }}">Meet The Chef</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="{{ route('portfolio') }}">Portfolio</a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('portfolio') }}">Portfolio</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('portfolioDetails') }}">Portfolio Details</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="{{ route('blog') }}">Blog</a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('blog') }}">Blog</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('blogDetails') }}">Blog Details</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#">Pages</a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('contact') }}">Contact</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('reservations') }}">Reservations</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('gallery') }}">Gallery</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('error404') }}">404 Page</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('comming') }}">Comming Soon</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header Section -->