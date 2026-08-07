@extends('layout.layout')

@php
    $title='Portfolio';
    $subTitle = 'Portfolio Details';
@endphp

@section('content')

<!-- Start Content  -->
<section>
    <div class="ak-height-150 ak-height-lg-60"></div>
    <div class="ak-portfolio-details-border"></div>
    <div>
        <div class="container">
            <div class="portfolio-section">
                <div class="portfolio-info">
                    <h2 class="anim-title-3 objects-up-down">Italian Thai</h2>
                    <div class="d-inline">
                        <h6>Category : <span>Desserts</span></h6>
                        <h6>Date : <span>5 July 2023</span></h6>
                        <h6>Tags : <span>Tasty, Sweet</span></h6>
                    </div>
                </div>
                <div class="portfolio-slider">
                    <div class="ak-slider ak-slider-4">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/img/portfolio.jpg') }}" alt="portfolio" class="img-fluid">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('assets/img/portfolio_details.jpg') }}" alt="portfolio" class="img-fluid">
                            </div>

                        </div>
                        <div class="ak-swiper-controll-4">
                            <div class="ak-swiper-navigation-wrap-4">
                                <div class="ak-swiper-button-prev-4">
                                    <div class="btn-cricle"></div>
                                    <div class="btn-arrow">
                                        <svg width="40" height="55" viewBox="0 0 54 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1166_8458)">
                                                <path d="M16.6309 25.4001L25.1406 16.8902C25.4463 16.5333 25.9835 16.4917 26.3405 16.7974C26.6974 17.1031 26.739 17.6403 26.4333 17.9973C26.4048 18.0306 26.3738 18.0617 26.3405 18.0901L19.2859 25.1532H52.9762C53.4461 25.1532 53.8271 25.5343 53.8271 26.0043C53.8271 26.4743 53.4461 26.8552 52.9762 26.8552H19.2859L26.3405 33.9098C26.6974 34.2155 26.739 34.7527 26.4333 35.1097C26.1275 35.4666 25.5904 35.5082 25.2334 35.2025C25.2001 35.174 25.1691 35.1429 25.1406 35.1097L16.6308 26.5999C16.3009 26.2681 16.3009 25.732 16.6309 25.4001Z" fill="#FFD28D" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ak-swiper-button-next-4">
                                    <div class="btn-cricle"></div>
                                    <div class="btn-arrow">
                                        <svg width="40" height="55" viewBox="0 0 55 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1166_8452)">
                                                <path d="M38.0234 25.4001L29.5137 16.8902C29.208 16.5333 28.6708 16.4917 28.3138 16.7974C27.9569 17.1031 27.9153 17.6403 28.221 17.9973C28.2495 18.0306 28.2805 18.0617 28.3138 18.0901L35.3684 25.1532H1.6781C1.20816 25.1532 0.827148 25.5343 0.827148 26.0043C0.827148 26.4743 1.20816 26.8552 1.6781 26.8552H35.3684L28.3138 33.9098C27.9569 34.2155 27.9153 34.7527 28.221 35.1097C28.5268 35.4666 29.0639 35.5082 29.4209 35.2025C29.4542 35.174 29.4852 35.1429 29.5137 35.1097L38.0235 26.5999C38.3534 26.2681 38.3534 25.732 38.0234 25.4001Z" fill="#FFD28D" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ak-height-100 ak-height-lg-60"></div>
            <h3 class="ak-white-color">Ingredient</h3>
            <div class="ak-height-30 ak-lg-height-30"></div>
            <div class="portfolio-details">
                <div class="portfolio-details-text">
                    <p>Lorem to our restaurant, where culinary artistry meets exceptional dining experiences.
                        At, we strive to create a gastronomic haven that. Lorem to our restaurant, where
                        culinary artistry meets exceptional dining experiences.
                        At, we strive to create a gastronomic haven that tantalizes your taste buds and leaves
                        you with unforgettable memories.</p>
                    <div class="ak-height-30 ak-height-lg-15"></div>
                    <p>Lorem to our restaurant, where culinary artistry meets exceptional dining experiences.
                        At, we strive to create a gastronomic haven that tantalizes your taste buds and leaves
                        you with unforgettable memories. </p>
                </div>
                <div class="portfolio-details-icon">
                    <h6>Share:</h6>
                    <a href="https://www.facebook.com/" class="fb-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="14" viewBox="0 0 9 14" fill="none">
                            <path d="M2.42172 14H5.36909V8.09789H8.02467L8.31646 5.16526H5.36909V3.68421C5.36909 3.48879 5.44672 3.30137 5.58491 3.16318C5.72309 3.025 5.91051 2.94737 6.10593 2.94737H8.31646V0H6.10593C5.12882 0 4.19173 0.388157 3.5008 1.07908C2.80988 1.77 2.42172 2.7071 2.42172 3.68421V5.16526H0.948039L0.65625 8.09789H2.42172V14Z" fill="white" />
                        </svg>
                    </a>
                    <a href="https://twitter.com/?lang=en" class="twitter-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="14" viewBox="0 0 18 14" fill="none">
                            <path d="M5.87735 14C12.5509 14 16.2005 8.61356 16.2005 3.94216C16.2005 3.78925 16.2005 3.63705 16.1902 3.48558C16.9005 2.98495 17.5135 2.36505 18.0006 1.65492C17.3385 1.94112 16.6361 2.12891 15.9168 2.21201C16.674 1.77 17.2407 1.07505 17.5114 0.256444C16.7993 0.668121 16.0203 0.95826 15.208 1.11434C14.6609 0.547585 13.9374 0.172293 13.1493 0.0465363C12.3613 -0.0792206 11.5527 0.051568 10.8486 0.418664C10.1446 0.785761 9.58431 1.3687 9.25454 2.07728C8.92478 2.78585 8.84389 3.58057 9.0244 4.33845C7.58197 4.26801 6.17085 3.90284 4.88265 3.26663C3.59445 2.63043 2.45795 1.73741 1.54693 0.645548C1.08308 1.4238 0.941087 2.34503 1.14985 3.2217C1.35861 4.09837 1.90243 4.86457 2.67062 5.36433C2.09315 5.34753 1.5283 5.19561 1.02377 4.92138V4.96661C1.024 5.78273 1.31394 6.57365 1.84442 7.20524C2.3749 7.83684 3.11326 8.27024 3.9343 8.43194C3.4002 8.57369 2.83986 8.59432 2.2963 8.49224C2.52811 9.19464 2.97944 9.8089 3.58716 10.2491C4.19489 10.6893 4.92862 10.9335 5.68577 10.9475C4.93346 11.5234 4.07202 11.9491 3.1507 12.2004C2.22938 12.4517 1.26626 12.5236 0.316406 12.412C1.97544 13.4496 3.90591 14.0001 5.87735 13.9978" fill="white" />
                        </svg>
                    </a>
                    <a href="https://bd.linkedin.com/" class="linkedin-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M4.62565 3.33302C4.62547 3.68665 4.48483 4.02571 4.23466 4.27564C3.98448 4.52556 3.64527 4.66587 3.29165 4.66569C2.93803 4.66551 2.59896 4.52487 2.34904 4.27469C2.09911 4.02452 1.95881 3.68531 1.95898 3.33169C1.95916 2.97807 2.09981 2.639 2.34998 2.38908C2.60015 2.13915 2.93936 1.99885 3.29298 1.99902C3.64661 1.9992 3.98567 2.13985 4.2356 2.39002C4.48552 2.64019 4.62583 2.9794 4.62565 3.33302ZM4.66565 5.65302H1.99898V13.9997H4.66565V5.65302ZM8.87898 5.65302H6.22565V13.9997H8.85232V9.61969C8.85232 7.17969 12.0323 6.95302 12.0323 9.61969V13.9997H14.6657V8.71302C14.6657 4.59969 9.95898 4.75302 8.85232 6.77302L8.87898 5.65302Z" fill="white" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Content  -->

@endsection