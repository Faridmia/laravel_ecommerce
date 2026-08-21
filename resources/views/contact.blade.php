@extends('layouts.app')

@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="container">
        <div class="page-header page-header-big text-center" style="background-image: url('{{ asset('assets/images/contact-header-bg.jpg') }}')">
            <h1 class="page-title text-white">Contact us<span class="text-white">keep in touch with us</span></h1>
        </div><!-- End .page-header -->
    </div><!-- End .container -->

    <div class="page-content pb-0 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-2 mb-lg-0">
                    <h2 class="title mb-3">Contact Information</h2><!-- End .title -->
                    <p class="mb-3" style="font-size:1.4rem;">We are here to help you with any questions or concerns you might have. Feel free to reach out to us using the contact details below or send us a message through the form.</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="contact-info">
                                <h3 style="font-weight: 500; font-size:1.6rem; color: #333; margin-bottom:1rem;">The Office</h3>
                                <ul class="contact-list" style="line-height: 2; font-size: 1.4rem; list-style:none; padding:0;">
                                    <li class="mb-1">
                                        <i class="icon-map-marker" style="margin-right: 0.8rem; color: #c96;"></i>
                                        Dhaka, Bangladesh
                                    </li>
                                    <li class="mb-1">
                                        <i class="icon-phone" style="margin-right: 0.8rem; color: #c96;"></i>
                                        <a href="tel:#" style="color: inherit;">+880 1234 567890</a>
                                    </li>
                                    <li class="mb-1">
                                        <i class="icon-envelope" style="margin-right: 0.8rem; color: #c96;"></i>
                                        <a href="mailto:info@molla.com" style="color: inherit;">info@molla.com</a>
                                    </li>
                                </ul><!-- End .contact-list -->
                            </div><!-- End .contact-info -->
                        </div><!-- End .col-sm-6 -->

                        <div class="col-sm-6">
                            <div class="contact-info">
                                <h3 style="font-weight: 500; font-size:1.6rem; color: #333; margin-bottom:1rem;">Office Hours</h3>
                                <ul class="contact-list" style="line-height: 2; font-size: 1.4rem; list-style:none; padding:0;">
                                    <li class="mb-1">
                                        <i class="icon-clock-o" style="margin-right: 0.8rem; color: #c96;"></i>
                                        <span class="text-dark" style="font-weight: 500;">Monday - Saturday</span> <br>9am - 8pm BDT
                                    </li>
                                    <li class="mb-1">
                                        <i class="icon-calendar" style="margin-right: 0.8rem; color: #c96;"></i>
                                        <span class="text-dark" style="font-weight: 500;">Sunday</span> <br>Closed
                                    </li>
                                </ul><!-- End .contact-list -->
                            </div><!-- End .contact-info -->
                        </div><!-- End .col-sm-6 -->
                    </div><!-- End .row -->
                </div><!-- End .col-lg-6 -->

                <div class="col-lg-6">
                    <h2 class="title mb-3">Got Any Questions?</h2><!-- End .title -->
                    <p class="mb-2" style="font-size:1.4rem;">Use the form below to get in touch with our team</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="contact-form mb-3">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <input type="text" class="form-control" id="cname" name="name" placeholder="Name *" value="{{ old('name') }}" required style="height: 40px; font-size:1.3rem;">
                            </div><!-- End .col-sm-6 -->

                            <div class="col-sm-6 mb-2">
                                <input type="email" class="form-control" id="cemail" name="email" placeholder="Email *" value="{{ old('email') }}" required style="height: 40px; font-size:1.3rem;">
                            </div><!-- End .col-sm-6 -->
                        </div><!-- End .row -->

                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <input type="tel" class="form-control" id="cphone" name="phone" placeholder="Phone" value="{{ old('phone') }}" style="height: 40px; font-size:1.3rem;">
                            </div><!-- End .col-sm-6 -->

                            <div class="col-sm-6 mb-2">
                                <input type="text" class="form-control" id="csubject" name="subject" placeholder="Subject" value="{{ old('subject') }}" style="height: 40px; font-size:1.3rem;">
                            </div><!-- End .col-sm-6 -->
                        </div><!-- End .row -->

                        <div class="mb-3">
                            <textarea class="form-control" cols="30" rows="4" id="cmessage" name="message" required placeholder="Message *" style="font-size:1.3rem; padding: 10px;">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm">
                            <span>SUBMIT</span>
                            <i class="icon-long-arrow-right"></i>
                        </button>
                    </form><!-- End .contact-form -->
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <hr class="mt-4 mb-5">
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
@endsection
