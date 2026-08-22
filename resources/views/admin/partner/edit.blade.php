@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Partner</h1>
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
                        <h3 class="card-title fw-bold text-secondary">Partner Details</h3>
                    </div>
                    <form action="{{ route('admin.partner.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <label class="form-label fw-semibold text-secondary">Partner Name (Optional)</label>
                                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" class="form-control" placeholder="e.g. Brand Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Link URL (Optional)</label>
                                    <input type="text" name="link" value="{{ old('link', $partner->link) }}" class="form-control" placeholder="e.g. https://brandwebsite.com or #">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Partner Logo Image</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted d-block mb-2">Suggested size: 150x80 px. Leave empty to keep the current image.</small>
                                @if(!empty($partner->image))
                                    <div class="mt-2">
                                        <label class="form-label d-block fw-semibold text-secondary">Current Logo:</label>
                                        <div class="bg-light p-2 border d-inline-block">
                                            <img src="{{ $partner->getImageUrl() }}" alt="current partner logo" style="max-height: 80px; object-fit: contain;">
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                        <div class="card-footer bg-transparent py-3">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold me-2">Save Changes</button>
                            <a href="{{ route('admin.partner.list') }}" class="btn btn-secondary px-4 fw-semibold">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
