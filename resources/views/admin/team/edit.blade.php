@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Team Member</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Member Details</h3>
                    </div>
                    <form action="{{ route('admin.team.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Full Name *</label>
                                    <input type="text" name="name" value="{{ old('name', $member->name) }}" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Designation / Role *</label>
                                    <input type="text" name="designation" value="{{ old('designation', $member->designation) }}" class="form-control" placeholder="e.g. Founder & CEO" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Photo (Image)</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted">Leave empty to keep current photo.</small>
                                <div class="mt-2">
                                    <img src="{{ $member->getImageUrl() }}" alt="current photo" class="rounded border" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Facebook URL</label>
                                    <input type="text" name="facebook_link" value="{{ old('facebook_link', $member->facebook_link) }}" class="form-control" placeholder="https://facebook.com/...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Twitter URL</label>
                                    <input type="text" name="twitter_link" value="{{ old('twitter_link', $member->twitter_link) }}" class="form-control" placeholder="https://twitter.com/...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Instagram URL</label>
                                    <input type="text" name="instagram_link" value="{{ old('instagram_link', $member->instagram_link) }}" class="form-control" placeholder="https://instagram.com/...">
                                </div>
                            </div>

                        </div>
                        <div class="card-footer bg-transparent py-3">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold me-2">Save Changes</button>
                            <a href="{{ route('admin.team.list') }}" class="btn btn-secondary px-4 fw-semibold">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
