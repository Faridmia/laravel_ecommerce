@extends('layouts.app')

@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->
    
    <div class="container">
        <div class="page-header page-header-big text-center" style="background-image: url('{{ asset('assets/images/about-header-bg.jpg') }}')">
            <h1 class="page-title text-white">{{ $page->title }}<span class="text-white">Who we are</span></h1>
        </div><!-- End .page-header -->
    </div><!-- End .container -->

    <div class="page-content pb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <h2 class="title">{{ $page->about_vision_title }}</h2>
                    <p>{{ $page->about_vision_description }}</p>
                </div><!-- End .col-lg-6 -->
                
                <div class="col-lg-6">
                    <h2 class="title">{{ $page->about_mission_title }}</h2>
                    <p>{{ $page->about_mission_description }}</p>
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <div class="mb-5"></div><!-- End .mb-4 -->
        </div><!-- End .container -->

        <div class="bg-light-2 pt-6 pb-5 mb-6 mb-lg-8">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 mb-3 mb-lg-0">
                        <h2 class="title">{{ $page->about_who_we_are_title }}</h2>
                        <p class="lead text-primary mb-3">Pellentesque odio nisi, euismod pharetra a ultricies <br>in diam. Sed arcu. Cras consequat</p>
                        <p class="mb-2">{{ $page->about_who_we_are_description }}</p>

                        <a href="{{ url('blog') }}" class="btn btn-sm btn-minwidth btn-outline-primary-2">
                            <span>VIEW OUR NEWS</span>
                            <i class="icon-long-arrow-right"></i>
                        </a>
                    </div><!-- End .col-lg-5 -->

                    <div class="col-lg-6 offset-lg-1">
                        <div class="about-images">
                            <img src="{{ asset('assets/images/about/img-1.jpg') }}" alt="" class="about-img-front">
                            <img src="{{ asset('assets/images/about/img-2.jpg') }}" alt="" class="about-img-back">
                        </div><!-- End .about-images -->
                    </div><!-- End .col-lg-6 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .bg-light-2 pt-6 pb-6 -->

        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="brands-text">
                        <h2 class="title">The world's premium design brands in one destination.</h2>
                        <p>Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nis</p>
                    </div><!-- End .brands-text -->
                </div><!-- End .col-lg-5 -->
                <div class="col-lg-7">
                    <div class="brands-display">
                        <div class="row justify-content-center">
                            @for($i = 1; $i <= 9; $i++)
                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="{{ asset('assets/images/brands/' . $i . '.png') }}" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->
                            @endfor
                        </div><!-- End .row -->
                    </div><!-- End .brands-display -->
                </div><!-- End .col-lg-7 -->
            </div><!-- End .row -->

            <hr class="mt-4 mb-6">

            <h2 class="title text-center mb-4">Meet Our Team</h2>

            <div class="row">
                @foreach($teams as $team)
                    <div class="col-md-4">
                        <div class="member member-anim text-center">
                            <figure class="member-media">
                                <img src="{{ $team->getImageUrl() }}" alt="member photo">

                                <figcaption class="member-overlay">
                                    <div class="member-overlay-content">
                                        <h3 class="member-title">{{ $team->name }}<span>{{ $team->designation }}</span></h3>
                                        <p>Professional team member dedicated to your service.</p> 
                                        <div class="social-icons social-icons-simple">
                                            @if($team->facebook_link)
                                                <a href="{{ $team->facebook_link }}" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                            @endif
                                            @if($team->twitter_link)
                                                <a href="{{ $team->twitter_link }}" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                            @endif
                                            @if($team->instagram_link)
                                                <a href="{{ $team->instagram_link }}" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            @endif
                                        </div><!-- End .soial-icons -->
                                    </div><!-- End .member-overlay-content -->
                                </figcaption><!-- End .member-overlay -->
                            </figure><!-- End .member-media -->
                            <div class="member-content">
                                <h3 class="member-title">{{ $team->name }}<span>{{ $team->designation }}</span></h3>
                            </div><!-- End .member-content -->
                        </div><!-- End .member -->
                    </div><!-- End .col-md-4 -->
                @endforeach
            </div><!-- End .row -->
        </div><!-- End .container -->

        <div class="mb-2"></div><!-- End .mb-2 -->

        @if($testimonials->count() > 0)
            <div class="about-testimonials bg-light-2 pt-6 pb-6">
                <div class="container">
                    <h2 class="title text-center mb-3">What Customers Say About Us</h2>

                    <div class="owl-carousel owl-simple owl-testimonials-photo" data-toggle="owl" 
                        data-owl-options='{
                            "nav": false, 
                            "dots": true,
                            "margin": 20,
                            "loop": false,
                            "responsive": {
                                "1200": {
                                    "nav": true
                                }
                            }
                        }'>
                        @foreach($testimonials as $testimonial)
                            <blockquote class="testimonial text-center">
                                <img src="{{ $testimonial->getImageUrl() }}" alt="user">
                                <p>“ {{ $testimonial->review }} ”</p>
                                <cite>
                                    {{ $testimonial->name }}
                                    <span>{{ $testimonial->designation }}</span>
                                </cite>
                            </blockquote><!-- End .testimonial -->
                        @endforeach
                    </div><!-- End .testimonials-slider owl-carousel -->
                </div><!-- End .container -->
            </div><!-- End .bg-light-2 pt-5 pb-6 -->
        @endif
    </div><!-- End .page-content -->
</main>
@endsection
