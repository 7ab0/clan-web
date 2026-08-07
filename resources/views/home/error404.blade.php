<!DOCTYPE html>
<html class="no-js" lang="en">

<!-- Start head  -->
<x-head />
<!-- End head  -->

<body>

    <!-- Start preloader  -->
    <x-preloader />
    <!-- End preloader  -->

    <!-- Start Header Section -->
    <x-header />
    <!-- End Header Section -->

    <div id="scrollsmoother-container">

        <div class="section-all-item-center" data-src="{{ asset('assets/img/errorBg.png') }}">
            <div class="border-comming-soon-colum-right"></div>
            <div class="border-comming-soon-top"></div>
            <div class="container text-center">
                <h2 class="item-title-number">404</h2>
                <h2 class="item-title">Sorry! The Page isn't Found Here</h2>
                <p class="item-subtext">Fortunately, since it is mainly a client-side issue, it is relatively easy for
                    website owners to fix the 404 error. This article will explain the possible causes of error 404 and
                    show four effective methods to resolve it.Fortunately, since
                    it is mainly a client-side issue, it is relatively easy for website owners to fix the 404 error.</p>
                <a href="{{ route('index') }}">
                    <div class="ak-btn style-5 mt-4">
                        Back to Home
                    </div>
                </a>
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
    <x-script />
    <!-- End script Section -->

</body>

</html>