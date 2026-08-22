@extends('layouts.app')

@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}')">
        <div class="container">
            <h1 class="page-title text-dark">{{ $page->title }}</h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content text-start mt-4">
        <div class="container">
            {!! $page->description !!}
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main>
@endsection
