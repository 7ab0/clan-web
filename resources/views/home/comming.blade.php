<!DOCTYPE html>
<html class="no-js" lang="en">

<!-- Start head Section -->
<x-head/>
<!-- End head Section -->

<body>

    <!-- Start preloader  -->
    <x-preloader/>
    <!-- End preloader  -->

    <!-- Start Header Section -->
    <header class="ak-site_header ak-style1 ak-sticky_header ak-site_header_full_width">
        <div class="header-top">
            <div class="wrapper">
                <div class="header-logo">
                    <a href="room.php" class="logo">Book Now</a>
                </div>
                <div class="center-log">
                    <a href="{{ route('index') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="..."></a>
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
                        <a href="{{ route('blog') }}">Blog</a>
                        <img class="top-main-menu-img menu-img" src="{{ asset('assets/img/fullWM_menu.jpg') }}" alt="...">
                    </li>
                    <li class="top-main-menu-li">
                        <a href="../hotel-resort/resort.php">Resort</a>
                        <img class="top-main-menu-img" src="{{ asset('assets/img/fullWM_chef.jpg') }}" alt="...">
                    </li>
                    <li class="top-main-menu-li">
                        <a href="{{ route('index') }}">Hotel</a>
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
                            <img src="{{ asset('assets/img/logo.png') }}" alt="...">
                        </a>
                    </div>
                    <div class="ak-main_header_right">
                        <div class="ak-nav ak-medium">
                            <ul class="ak-nav_list">
                                <li class="menu-item-has-children">
                                    <a href="{{ route('index') }}">Home</a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('index') }}">Hotel</a>
                                        </li>
                                        <li>
                                            <a href="../hotel-resort/resort.php">Resort</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="../hotel-resort/about-hotel-resort.php">About</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="room.php">Room</a>
                                    <ul>
                                        <li>
                                            <a href="room.php">Room</a>
                                        </li>
                                        <li>
                                            <a href="single-room.php">Single Room</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="event-list.php">Event</a>
                                    <ul>
                                        <li>
                                            <a href="event-list.php">Event</a>
                                        </li>
                                        <li>
                                            <a href="event-single-page.php">Single Event</a>
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
                                            <a href="restaurent.php">Restaurant</a>
                                        </li>
                                        <li>
                                            <a href="spa-wellness.php">Spa Wellness</a>
                                        </li>
                                        <li>
                                            <a href="hotel-facilities.php">Hotel Facilities</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('comming') }}">Comming Soon</a>
                                        </li>
                                        <li>
                                            <a href="faq.php">faq</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('error404') }}">404 Page</a>
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

    <div id="scrollsmoother-container">
        <div class="section-all-item-center" data-src="{{ asset('assets/img/errorBg.png') }}">
            <div class="border-comming-soon-colum-right"></div>
            <div class="border-comming-soon-top"></div>
            <h2 class="item-title">Coming Soon</h2>
            <div class="container">
                <div class="date-section" id="comming-section">
                    <div class="timmer days">
                        <h1 id="days" class="number number ak-stroke-text"></h1>
                        <p class="text">Days</p>
                    </div>
                    <div class="timmer hours">
                        <h1 id="hours" class="number number ak-stroke-text"></h1>
                        <p class="text">Hours</p>
                    </div>
                    <div class="timmer minutes">
                        <h1 id="minutes" class="number number ak-stroke-text"></h1>
                        <p class="text">Minutes</p>
                    </div>
                    <div class="timmer seconds">
                        <h1 id="seconds" class="number number ak-stroke-text ak-number">0</h1>
                        <p class="text">Seconds</p>
                    </div>
                </div>
            </div>
            <div class="border-comming-soon-colum-left"></div>
            <div class="border-comming-soon-bottom"></div>
        </div>

        <!-- Start Footer -->
        <footer>
            <span class="ak-scrollup">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 10L1.7625 11.7625L8.75 4.7875V20H11.25V4.7875L18.225 11.775L20 10L10 0L0 10Z" fill="currentColor" />
                </svg>
            </span>
        </footer>
        <!-- End Footer -->
    </div>

    <!-- Start script Section -->
    <x-script/>
    <!-- End script Section -->

</body>

</html>