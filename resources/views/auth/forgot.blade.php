@extends('layouts.app')
@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content pb-0 mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 mb-5" style="border-top: 3px solid #cc9966 !important;">
                        <div class="card-body p-5">
                            <h2 class="title text-center mb-3">Forgot Password</h2>
                            
                            @if (session('success'))
                                <div class="alert alert-success p-3 text-center mb-4" role="alert" style="font-size: 1.3rem;">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger p-3 mb-4" role="alert" style="font-size: 1.3rem;">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <p class="text-center mb-4" style="font-size: 1.4rem; line-height: 1.6; color: #666;">
                                Forgot your password? No problem. Just enter your email address and we will send you a link to reset your password.
                            </p>

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="email" style="font-size: 1.4rem; font-weight: 500; color: #333;">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required style="height: 40px; font-size:1.3rem;">
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm">
                                        <span>Email Reset Link</span>
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
