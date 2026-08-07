@extends('layout.layout')

@php
    $title='Blog';
    $subTitle = 'Blog';
@endphp

@section('content')

    <!-- Start All Blog -->
    <div class="container">
        <div class="ak-height-150 ak-height-lg-60"></div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="pagination-container">
            <div class="col ak-border  drop-anim-gallery">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_1.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border drop-anim-gallery">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_2.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-right">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_3.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border  drop-anim-gallery">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_4.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border  drop-anim-gallery">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_5.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-right">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_6.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-bottom  drop-anim-gallery">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_7.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border border-none-bottom  drop-anim-gallery">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_8.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col ak-border  border-none-bottom border-none-right">
                <div class="blog h-100">
                    <img src="{{ asset('assets/img/blog_9.jpg') }}" class="blog-img-top" alt="...">
                    <div class="blog-body">
                        <p class="blog-time">06 June 2023</p>
                        <a href="{{ route('blogDetails') }}">
                            <h6 class="blog-title">Exquisite Dining Make Moment</h6>
                        </a>
                        <a href="{{ route('blogDetails') }}" class="blog-text">Read More</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Blog -->

@endsection
