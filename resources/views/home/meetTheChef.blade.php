@extends('layout.layout')

@php
    $title='About Chef';
    $subTitle = 'About Chef';
@endphp

@section('content')

<!-- Start About Content -->
<section>
    <div class="ak-height-150 ak-height-lg-60"></div>
    <div class="container">
        <div class="meet-the-content-about-section">
            <div class="about-info">
                <div class="ak-section-heading ak-style-1 ak-color-1">
                    <p>Head of Chef</p>
                    <h2 class="ak-section-title anim-title-3">About Alex Smith</h2>
                </div>
                <div class="ak-height-25 ak-height-lg-25"></div>
                <p>Lorem to our restaurant, where culinary artistry meets exceptional dining experiences. At, we
                    strive to create a gastronomic haven that tantalizes your taste buds and leaves you with
                    unforgettable memories. Welcome to our restaurant,
                    where culinary artistry meets exceptional dining experiences. At, we strive to create a
                    gastronomic.
                </p>
                <div class="ak-height-25 ak-height-lg-25"></div>
                <p>Lorem to our restaurant, where culinary artistry meets exceptional dining experiences. At, we
                    strive to create a gastronomic haven that. Lorem to our restaurant, where culinary artistry
                    meets exceptional dining exp eriences. At,
                    we strive to create a gastronomic haven that.</p>
                <div class="ak-height-45 ak-height-lg-30"></div>
                <div class="text-btn">
                    <a href="https://www.youtube.com/watch?v=UsD1MhKBmD4" class="text-btn1 ak-video-open">
                        View Expertise
                    </a>
                </div>

            </div>
            <div class="about-img">
                <img src="{{ asset('assets/img/meetAbout.jpg') }}" class="imagesZoom" data-speed="1.1" alt="meetAbout">
            </div>
            <div class="about-social">
                <a href="#">FACEBOOK</a>
                <a href="#">LINKEDING</a>
                <a href="#">INSTAGRAM</a>
            </div>

        </div>

    </div>
</section>
<!-- End About Content -->

@endsection