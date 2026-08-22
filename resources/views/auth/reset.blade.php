@extends('layouts.app')
@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content pb-0 mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 mb-5" style="border-top: 3px solid #cc9966 !important;">
                        <div class="card-body p-5">
                            <h2 class="title text-center mb-3">Reset Password</h2>

                            @if ($errors->any())
                                <div class="alert alert-danger p-3 mb-4" role="alert" style="font-size: 1.3rem;">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group mb-3">
                                    <label for="email" style="font-size: 1.4rem; font-weight: 500; color: #333;">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $email) }}" required readonly style="height: 40px; font-size:1.3rem; background-color: #f9f9f9;">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password" style="font-size: 1.4rem; font-weight: 500; color: #333;">New Password *</label>
                                    <input type="password" class="form-control" id="password" name="password" required style="height: 40px; font-size:1.3rem;">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="password_confirmation" style="font-size: 1.4rem; font-weight: 500; color: #333;">Confirm Password *</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required style="height: 40px; font-size:1.3rem;">
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm">
                                        <span>Reset Password</span>
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
