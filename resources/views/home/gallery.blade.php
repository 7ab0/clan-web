@extends('layout.layout')

@php
    $title='Gallery';
    $subTitle = 'Gallery';
@endphp

@section('content')

<div class="ak-height-180 ak-height-lg-90"></div>

<!-- Start Gallery -->
<section class="container">
    <div id="static-thumbnails">
        <div class="row row-cols-1 row-cols-md-2  row-cols-xl-3 g-5">
            <div class="col ak-border drop-anim-gallery">
                <div class="gallery ak-bg" data-src="{{ asset('assets/img/gallery_1.jpg') }}">
                    <div class="gallery style-1">
                        <div class="gallery-hover">
                            <div class="gallery-hover-icon">
                                <a href="{{ asset('assets/img/gallery_1.jpg') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" viewBox="0 0 41 41" fill="none">
                                        <rect x="0.507812" y="19.7305" width="40" height="1" fill="#FFD28D" />
                                        <rect x="20.0078" y="0.730469" width="1" height="40" fill="#FFD28D" />
                                    </svg>
                                </a>
                            </div>
                            <div class="gallery-hover-info">
                                <a href="#">
                                    <h6>Paella Valencene</h6>
                                    <p>Italian</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ak-border drop-anim-gallery">
                <div class="gallery ak-bg" data-src="{{ asset('assets/img/gallery_2.jpg') }}">
                    <div class="gallery style-1">
                        <div class="gallery-hover">
                            <div class="gallery-hover-icon">
                                <a href="{{ asset('assets/img/gallery_2.jpg') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" viewBox="0 0 41 41" fill="none">
                                        <rect x="0.507812" y="19.7305" width="40" height="1" fill="#FFD28D" />
                                        <rect x="20.0078" y="0.730469" width="1" height="40" fill="#FFD28D" />
                                    </svg>
                                </a>
                            </div>
                            <div class="gallery-hover-info">
                                <a href="#">
                                    <h6>Paella Valencene</h6>
                                    <p>Italian</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-right">
                <div class="gallery ak-bg" data-src="{{ asset('assets/img/gallery_3.jpg') }}">
                    <div class="gallery style-1">
                        <div class="gallery-hover">
                            <div class="gallery-hover-icon">
                                <a href="{{ asset('assets/img/gallery_3.jpg') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" viewBox="0 0 41 41" fill="none">
                                        <rect x="0.507812" y="19.7305" width="40" height="1" fill="#FFD28D" />
                                        <rect x="20.0078" y="0.730469" width="1" height="40" fill="#FFD28D" />
                                    </svg>
                                </a>
                            </div>
                            <div class="gallery-hover-info">
                                <a href="#">
                                    <h6>Paella Valencene</h6>
                                    <p>Italian</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-bottom drop-anim-gallery">
                <div class="gallery ak-bg" data-src="{{ asset('assets/img/gallery_4.jpg') }}">
                    <div class="gallery style-1">
                        <div class="gallery-hover">
                            <div class="gallery-hover-icon">
                                <a href="{{ asset('assets/img/gallery_4.jpg') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" viewBox="0 0 41 41" fill="none">
                                        <rect x="0.507812" y="19.7305" width="40" height="1" fill="#FFD28D" />
                                        <rect x="20.0078" y="0.730469" width="1" height="40" fill="#FFD28D" />
                                    </svg>
                                </a>
                            </div>
                            <div class="gallery-hover-info">
                                <a href="#">
                                    <h6>Paella Valencene</h6>
                                    <p>Italian</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-bottom drop-anim-gallery">
                <div class="gallery ak-bg" data-src="{{ asset('assets/img/gallery_5.jpg') }}">
                    <div class="gallery style-1">
                        <div class="gallery-hover">
                            <div class="gallery-hover-icon">
                                <a href="{{ asset('assets/img/gallery_5.jpg') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" viewBox="0 0 41 41" fill="none">
                                        <rect x="0.507812" y="19.7305" width="40" height="1" fill="#FFD28D" />
                                        <rect x="20.0078" y="0.730469" width="1" height="40" fill="#FFD28D" />
                                    </svg>
                                </a>
                            </div>
                            <div class="gallery-hover-info">
                                <a href="#">
                                    <h6>Paella Valencene</h6>
                                    <p>Italian</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-right border-none-bottom">
                <div class="gallery ak-bg" data-src="{{ asset('assets/img/gallery_6.jpg') }}">
                    <div class="gallery style-1">
                        <div class="gallery-hover">
                            <div class="gallery-hover-icon">
                                <a href="{{ asset('assets/img/gallery_6.jpg') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" viewBox="0 0 41 41" fill="none">
                                        <rect x="0.507812" y="19.7305" width="40" height="1" fill="#FFD28D" />
                                        <rect x="20.0078" y="0.730469" width="1" height="40" fill="#FFD28D" />
                                    </svg>
                                </a>
                            </div>
                            <div class="gallery-hover-info">
                                <a href="#">
                                    <h6>Paella Valencene</h6>
                                    <p>Italian</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Gallery -->

@endsection