@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Profile Settings</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <div class="row">
            <!-- Left Column: Profile Settings Form -->
            <div class="col-md-7">
                <div class="card card-primary card-outline mb-4 shadow-sm">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                        <h3 class="card-title fw-bold">Profile Info</h3>
                    </div>
                    <!-- Form -->
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body px-4 py-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary fw-semibold">First Name</label>
                                    <input type="text" name="first_name" class="form-control form-control-lg" value="{{ old('first_name', $user->first_name) }}" required placeholder="Enter first name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary fw-semibold">Last Name</label>
                                    <input type="text" name="last_name" class="form-control form-control-lg" value="{{ old('last_name', $user->last_name) }}" required placeholder="Enter last name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary fw-semibold">Email address</label>
                                    <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email', $user->email) }}" required placeholder="Enter email address">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary fw-semibold">Phone</label>
                                    <input type="text" name="phone" class="form-control form-control-lg" value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Heading</label>
                                <input type="text" name="heading" class="form-control form-control-lg" value="{{ old('heading', $user->heading) }}" placeholder="e.g. Software Engineer, Store Manager">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Intro</label>
                                <textarea name="intro" class="form-control form-control-lg" rows="6" placeholder="Describe yourself...">{{ old('intro', $user->intro) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Profile Image</label>
                                <input type="file" name="profile_pic" class="form-control" id="profilePicInput" accept="image/*">
                                <div class="mt-3">
                                    <img id="profilePicPreview" src="{{ $user->getProfilePicUrl() }}" alt="Profile Preview" class="rounded shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #dee2e6;">
                                </div>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-top-0 pb-4 px-4 text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-4 fw-semibold">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Change Password Form -->
            <div class="col-md-5">
                <div class="card card-primary card-outline mb-4 shadow-sm">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                        <h3 class="card-title fw-bold">Change Password</h3>
                    </div>
                    <!-- Form -->
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        <div class="card-body px-4 py-3">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Old Password</label>
                                <input type="password" name="old_password" class="form-control form-control-lg" required placeholder="Enter old password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">New Password</label>
                                <input type="password" name="new_password" class="form-control form-control-lg" required placeholder="Enter new password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control form-control-lg" required placeholder="Confirm new password">
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-top-0 pb-4 px-4 text-end">
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('profilePicInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePicPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
