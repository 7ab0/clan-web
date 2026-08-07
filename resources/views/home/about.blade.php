@extends('layout.layout')

@php
    $title = 'About Us';
    $subTitle = 'About Us';
@endphp

@section('content')

<!-- Start About -->
<section class="ak-about-bg-color">
    <div class="ak-height-150 ak-height-lg-60"></div>
    <div class="ak-about ak-style-1">
        <div class="ak-about-bg-img ak-bg">
            <img class="imagesZoom" src="{{ asset('assets/img/about_bg.jpg') }}" alt="...">
        </div>
        <div class="ak-about-hr"></div>
        <div class="container">
            <div class="about-section ak-about-1">
                <div class="about-text-section">
                    <h2 class="about-title">Exquisite Dining Experience Fit for
                        <br><span class="anim-title-2">Royalty</span>
                    </h2>
                    <div class="ak-height-30 ak-height-lg-30"></div>
                    <p class="about-subtext">Welcome to our restaurant, where culinary artistry meets
                        exceptional dining experiences. At, we strive to create a gastronomic haven that
                        tantalizes your taste buds and leaves you with unforgettable memories.
                    </p>
                    <div class="ak-height-30 ak-height-lg-30"></div>
                    <p class="about-subtext">Lorem to our restaurant, where culinary artistry meets exceptional
                        dining experiences. At, we strive to create a gastronomic haven that.
                    </p>
                    <div class="ak-height-50 ak-height-lg-30"></div>
                    <div class="text-btn">
                        <a href="{{ route('about') }}" class="text-btn1">
                            Discover The Kitchen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- End About -->

<!-- Start Testimonial -->
<section class="container">
    <div class="ak-height-150 ak-height-lg-60"></div>
    <div class="ak-slider ak-slider-3">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="container">
                    <div class="testimonial-section">
                        <div class="testimonial-icon-1">
                            <img src="{{ asset('assets/img/testimonial_icon_l.svg') }}" alt="...">
                        </div>
                        <div class="testimonial-info-section">
                            <div class="testimonial-info">
                                <img src="{{ asset('assets/img/testimonial_1.jpg') }}" class="testimonial-info-img" alt="...">
                                <h6 class="testimonial-info-title">Steven K. Roberts</h6>
                                <p class="short-title">From USA</p>
                                <p class="testimonial-info-subtitle">“Their talented team of passionate chefs
                                    masterfully crafts each dish, combining the finest ingredients with
                                    innovative techniques to present culinary creations that are as visually
                                    stunning as they are delicious.”</p>
                            </div>
                        </div>
                        <div class="testimonial-icon-1">
                            <img src="{{ asset('assets/img/testimonial_icon_r.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="container">
                    <div class="testimonial-section">
                        <div class="testimonial-icon-1">
                            <img src="{{ asset('assets/img/testimonial_icon_l.svg') }}" alt="...">
                        </div>
                        <div class="testimonial-info-section">
                            <div class="testimonial-info">
                                <img src="{{ asset('assets/img/testimonial_1.jpg') }}" class="testimonial-info-img" alt="...">
                                <h6 class="testimonial-info-title">Steven K. Roberts</h6>
                                <p class="short-title">From USA</p>
                                <p class="testimonial-info-subtitle">“Their talented team of passionate chefs
                                    masterfully crafts each dish, combining the finest ingredients with
                                    innovative techniques to present culinary creations that are as visually
                                    stunning as they are delicious.”</p>
                            </div>
                        </div>
                        <div class="testimonial-icon-1">
                            <img src="{{ asset('assets/img/testimonial_icon_r.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="container">
                    <div class="testimonial-section">
                        <div class="testimonial-icon-1">
                            <img src="{{ asset('assets/img/testimonial_icon_l.svg') }}" alt="...">
                        </div>
                        <div class="testimonial-info-section">
                            <div class="testimonial-info">
                                <img src="{{ asset('assets/img/testimonial_1.jpg') }}" class="testimonial-info-img" alt="...">
                                <h6 class="testimonial-info-title">Steven K. Roberts</h6>
                                <p class="short-title">From USA</p>
                                <p class="testimonial-info-subtitle">“Their talented team of passionate chefs
                                    masterfully crafts each dish, combining the finest ingredients with
                                    innovative techniques to present culinary creations that are as visually
                                    stunning as they are delicious.”</p>
                            </div>
                        </div>
                        <div class="testimonial-icon-1">
                            <img src="{{ asset('assets/img/testimonial_icon_r.svg') }}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="ak-swiper-controll-3">
            <div class="ak-swiper-navigation-wrap">
                <div class="ak-swiper-button-prev-3">
                    <button class="btn-style-2 btn-size btn-style-round button-prev-next-2 rotate-svg" aria-disabled="false">
                        <svg width="20" height="14" xmlns="http://www.w3.org/2000/svg">
                            <g stroke="#fff" fill="none" fill-rule="evenodd">
                                <path d="M12.743 1.343L18.4 7l-5.657 5.657M18.4 7H.4"></path>
                            </g>
                        </svg>
                        <svg width="20" height="14" xmlns="http://www.w3.org/2000/svg">
                            <g stroke="#fff" fill="none" fill-rule="evenodd">
                                <path d="M12.743 1.343L18.4 7l-5.657 5.657M18.4 7H.4"></path>
                            </g>
                        </svg>
                    </button>

                </div>
                <div class="ak-swiper-button-next-3">
                    <button class="btn-style-2 btn-size btn-style-round button-prev-next-2" aria-disabled="false">
                        <svg width="20" height="14" xmlns="http://www.w3.org/2000/svg">
                            <g stroke="#fff" fill="none" fill-rule="evenodd">
                                <path d="M12.743 1.343L18.4 7l-5.657 5.657M18.4 7H.4"></path>
                            </g>
                        </svg>
                        <svg width="20" height="14" xmlns="http://www.w3.org/2000/svg">
                            <g stroke="#fff" fill="none" fill-rule="evenodd">
                                <path d="M12.743 1.343L18.4 7l-5.657 5.657M18.4 7H.4"></path>
                            </g>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Testimonial -->

<!-- Start Opening Hour -->
<section class="container">
    <div class="ak-height-150 ak-height-lg-60"></div>
    <div class="opening-hour type-2">
        <div class="opening-hour-img-section style-2">
            <img src="{{ asset('assets/img/about_open_hour.jpg') }}" class="imagesZoom opening-bg-img ak-bg" alt="...">
            <div class="overlap-opening-img"></div>
        </div>
        <div class="opening-hour-text-section type-2">
            <h2 class="opening-hour-title  anim-title-2">Opening Hours</h2>
            <div class="ak-height-30 ak-height-lg-30"></div>
            <p class="opening-hour-subtext">Lorem to our restaurant, where culinary artistry meets exceptional
                dining experiences. At, we strive to create a gastronomic haven that.</p>
            <div class="ak-height-30 ak-height-lg-30"></div>
            <div class="opening-hour-date">
                <p>SUNDAY - THURSDAY: 11:30AM - 11PM</p>
                <div class="opening-hour-hr"></div>
                <p> FRIDAY & SATURDAY: 11:30AM - 12AM</p>
            </div>
            <div class="ak-height-70 ak-height-lg-30"></div>
            <div class="text-btn">
                <a href="{{ route('reservations') }}" class="text-btn1">
                    Reservation
                </a>

            </div>
        </div>
    </div>
</section>
<!-- End  Opening Hour  -->

<div class="ak-height-150 ak-height-lg-60"></div>
<!-- Start Video -->
<div class="video-section">
    <img src="{{ asset('assets/img/aboutVideoBg.jpg') }}" alt="..." class="video-section-bg-img ak-bg imagesZoom" data-speed="1.1">
    <div class="video-section-btn">
        <a href="https://www.youtube.com/watch?v=UsD1MhKBmD4" class="ak-video-block ak-style1 ak-video-open">
            <span class="ak-player-btn ak-accent-color">
                <span></span>
            </span>
        </a>
    </div>
</div>
<!-- End Video -->

@endsection