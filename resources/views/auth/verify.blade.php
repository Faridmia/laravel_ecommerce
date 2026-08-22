@extends('layouts.app')
@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Verify Email</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content pb-0 mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 mb-5" style="border-top: 3px solid #cc9966 !important;">
                        <div class="card-body p-5">
                            <h2 class="title text-center mb-3">Verify Your Email Address</h2>
                            
                            @if (session('message'))
                                <div class="alert alert-success p-3 text-center mb-4" role="alert" style="font-size: 1.3rem;">
                                    {{ session('message') }}
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success p-3 text-center mb-4" role="alert" style="font-size: 1.3rem;">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <p class="text-center mb-4" style="font-size: 1.5rem; line-height: 1.6; color: #666;">
                                Before proceeding, please check your email for a verification link.
                                <br>If you did not receive the email, click the button below to request another.
                            </p>

                            <form class="d-block text-center" method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm">
                                        <span>Click here to request another</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
