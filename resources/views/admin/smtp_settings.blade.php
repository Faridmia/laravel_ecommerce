@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>SMTP Setting</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline mb-4 shadow-sm">
                    <form action="{{ route('admin.smtp_settings.update') }}" method="POST">
                        @csrf
                        <div class="card-body px-4 py-3">
                            
                            <!-- Website Name -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Website Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name', $settings->name) }}" required placeholder="Enter website name">
                            </div>

                            <hr class="my-4">

                            <!-- Mail Mailer -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mail Mailer <span class="text-danger">*</span></label>
                                <input type="text" name="mail_mailer" class="form-control" value="{{ old('mail_mailer', $settings->mail_mailer) }}" required placeholder="Enter mail mailer (e.g. smtp)">
                            </div>

                            <hr class="my-4">

                            <!-- Mail Host -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mail Host <span class="text-danger">*</span></label>
                                <input type="text" name="mail_host" class="form-control" value="{{ old('mail_host', $settings->mail_host) }}" required placeholder="Enter mail host (e.g. smtp.gmail.com)">
                            </div>

                            <hr class="my-4">

                            <!-- Mail Port -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mail Port <span class="text-danger">*</span></label>
                                <input type="text" name="mail_port" class="form-control" value="{{ old('mail_port', $settings->mail_port) }}" required placeholder="Enter mail port (e.g. 587)">
                            </div>

                            <hr class="my-4">

                            <!-- Mail Username -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mail Username <span class="text-danger">*</span></label>
                                <input type="text" name="mail_username" class="form-control" value="{{ old('mail_username', $settings->mail_username) }}" placeholder="Enter mail username (e.g. your_email@gmail.com)">
                            </div>

                            <hr class="my-4">

                            <!-- Mail Password -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mail Password <span class="text-danger">*</span></label>
                                <input type="password" name="mail_password" class="form-control" value="{{ old('mail_password', $settings->mail_password) }}" placeholder="Enter mail password">
                            </div>

                            <hr class="my-4">

                            <!-- Mail Encryption -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mail Encryption <span class="text-danger">*</span></label>
                                <input type="text" name="mail_encryption" class="form-control" value="{{ old('mail_encryption', $settings->mail_encryption) }}" placeholder="Enter mail encryption (e.g. tls or ssl)">
                            </div>

                            <hr class="my-4">

                            <!-- Mail From Address -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mail From Address <span class="text-danger">*</span></label>
                                <input type="email" name="mail_from_address" class="form-control" value="{{ old('mail_from_address', $settings->mail_from_address) }}" required placeholder="Enter mail from address (e.g. hello@example.com)">
                            </div>

                        </div>
                        <div class="card-footer px-4 py-3 bg-light text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
