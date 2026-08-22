@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Add New Slider</h1>
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
                        <h3 class="card-title fw-bold text-secondary">Slider Details</h3>
                    </div>
                    <form action="{{ route('admin.slider.insert') }}" method="POST" enctype="multipart/form-data">
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
                                    <label class="form-label fw-semibold text-secondary">Subtitle (Top text)</label>
                                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="form-control" placeholder="e.g. Topsale Collection">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Title (Main Heading)</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. Living Room &lt;br&gt; Furniture (can use &lt;br&gt;)">
                                    <small class="text-muted">You can use <code>&lt;br&gt;</code> to start a new line on the slide heading.</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Button Text</label>
                                    <input type="text" name="button_text" value="{{ old('button_text') }}" class="form-control" placeholder="e.g. SHOP NOW">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Button Link URL</label>
                                    <input type="text" name="button_link" value="{{ old('button_link') }}" class="form-control" placeholder="e.g. category/furniture or url('/')">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Slider Image *</label>
                                <input type="file" name="image" class="form-control" required>
                                <small class="text-muted">Suggested image size: 1920x600 px or larger landscape image.</small>
                            </div>

                        </div>
                        <div class="card-footer bg-transparent py-3">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold me-2">Save Slider</button>
                            <a href="{{ route('admin.slider.list') }}" class="btn btn-secondary px-4 fw-semibold">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
