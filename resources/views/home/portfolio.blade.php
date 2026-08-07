@extends('layout.layout')

@php
    $title='Portfolio';
    $subTitle = 'Portfolio';
@endphp

@section('content')

<!-- Start Portfolio -->
<div class="container">
    <div class="ak-height-150 ak-height-lg-60"></div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/food_item_1.jpg') }}" alt="..." />

                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Desserts
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_2.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_3.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_4.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_5.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_6.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_7.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_8.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('portfolioDetails') }}" class="ak-card ak-style-1">
                <div class="ak-card-img">
                    <img src="{{ asset('assets/img/portfolio_9.jpg') }}" alt="..." />
                </div>
                <div class="card-info">
                    <div class="card-text style-1">
                        <h5 class="card-title">
                            Spaghetti Carbonara
                        </h5>
                        <div class="card-subtitle">
                            Google Marketing
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- End Portfolio -->

@endsection
