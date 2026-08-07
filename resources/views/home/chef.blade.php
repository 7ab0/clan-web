@extends('layout.layout')

@php
    $title='Chefs';
    $subTitle = 'Our Chefs';
@endphp

@section('content')

<div class="ak-height-150 ak-height-lg-60"></div>

<!-- Start  all chef-->
<section class="container">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
        <div class="col">
            <div class="chef ak-bg" data-src="{{ asset('assets/img/chef_1.jpg') }}">
                <div class="chef-style-1">
                    <div class="chef-info">
                        <div class="chef-info-social">
                            <a href="https://www.facebook.com/">FACEBOOK</a>
                            <a href="https://bd.linkedin.com/">LINKEDING</a>
                            <a href="https://www.instagram.com/">INSTAGRAM</a>
                        </div>
                        <div class="chef-title">
                            <a href="{{ route('meetTheChef') }}">Byron Smith</a>
                            <p>Head of Chef</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="chef ak-bg" data-src="{{ asset('assets/img/chef_2.jpg') }}">
                <div class="chef-style-1">
                    <div class="chef-info">
                        <div class="chef-info-social">
                            <a href="https://www.facebook.com/">FACEBOOK</a>
                            <a href="https://bd.linkedin.com/">LINKEDING</a>
                            <a href="https://www.instagram.com/">INSTAGRAM</a>
                        </div>
                        <div class="chef-title">
                            <a href="{{ route('meetTheChef') }}">Craig Martin</a>
                            <p>Italian Specialist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="chef ak-bg" data-src="{{ asset('assets/img/chef_3.jpg') }}">
                <div class="chef-style-1">
                    <div class="chef-info">
                        <div class="chef-info-social">
                            <a href="https://www.facebook.com/">FACEBOOK</a>
                            <a href="https://bd.linkedin.com/">LINKEDING</a>
                            <a href="https://www.instagram.com/">INSTAGRAM</a>
                        </div>
                        <div class="chef-title">
                            <a href="{{ route('meetTheChef') }}">Ronald Adams</a>
                            <p>Thai Specialist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="chef ak-bg" data-src="{{ asset('assets/img/chef_4.jpg') }}">
                <div class="chef-style-1">
                    <div class="chef-info">
                        <div class="chef-info-social">
                            <a href="https://www.facebook.com/">FACEBOOK</a>
                            <a href="https://bd.linkedin.com/">LINKEDING</a>
                            <a href="https://www.instagram.com/">INSTAGRAM</a>
                        </div>
                        <div class="chef-title">
                            <a href="{{ route('meetTheChef') }}">Andrew Cannon</a>
                            <p>Andrew Cannon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="chef ak-bg" data-src="{{ asset('assets/img/chef_5.jpg') }}">
                <div class="chef-style-1">
                    <div class="chef-info">
                        <div class="chef-info-social">
                            <a href="https://www.facebook.com/">FACEBOOK</a>
                            <a href="https://bd.linkedin.com/">LINKEDING</a>
                            <a href="https://www.instagram.com/">INSTAGRAM</a>
                        </div>
                        <div class="chef-title">
                            <a href="{{ route('meetTheChef') }}">Wesley Story</a>
                            <p>Italian Specialist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="chef ak-bg" data-src="{{ asset('assets/img/chef_6.jpg') }}">
                <div class="chef-style-1">
                    <div class="chef-info">
                        <div class="chef-info-social">
                            <a href="https://www.facebook.com/">FACEBOOK</a>
                            <a href="https://bd.linkedin.com/">LINKEDING</a>
                            <a href="https://www.instagram.com/">INSTAGRAM</a>
                        </div>
                        <div class="chef-title">
                            <a href="{{ route('meetTheChef') }}">Michael Laseter</a>
                            <p>Italian Specialist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- End  all chef-->

@endsection