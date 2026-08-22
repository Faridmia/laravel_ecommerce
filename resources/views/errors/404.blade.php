@php
    $page = \App\Models\Page::getSlug('404');
    $meta_title = !empty($page->meta_title) ? $page->meta_title : "404 Not Found - Molla eCommerce";
    $meta_description = !empty($page->meta_description) ? $page->meta_description : "";
    $meta_keywords = !empty($page->meta_keywords) ? $page->meta_keywords : "";
@endphp

@extends('layouts.app')

@section('style')
<style>
    .error-content {
        background-color: #fafafa;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        padding-top: 150px;
        padding-bottom: 150px;
    }
    .error-title {
        font-size: 80px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 15px;
    }
    .error-message {
        font-size: 18px;
        color: #666666;
        margin-bottom: 30px;
    }
    .error-message p {
        font-size: 18px;
        color: #666666;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">404</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    @php
        $bg_image = asset('assets/images/backgrounds/error-bg.jpg');
        if (!empty($page->image) && file_exists(public_path('upload/pages/' . $page->image))) {
            $bg_image = asset('upload/pages/' . $page->image);
        }
    @endphp
    <div class="error-content text-center" style="background-image: url('{{ $bg_image }}')">
        <div class="container">
            <h1 class="error-title">{{ !empty($page->title) ? $page->title : 'Error 404' }}</h1><!-- End .error-title -->
            
            <div class="error-message">
                @if(!empty($page->description))
                    {!! $page->description !!}
                @else
                    <p>We are sorry, the page you've requested is not available.</p>
                @endif
            </div>

            <a href="{{ url('/') }}" class="btn btn-outline-primary-2 btn-minwidth-lg">
                <span>BACK TO HOMEPAGE</span>
                <i class="icon-long-arrow-right"></i>
            </a>
        </div><!-- End .container -->
    </div><!-- End .error-content -->
</main>
@endsection
