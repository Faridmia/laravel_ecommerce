@extends('admin.layouts.app')
@section('content')

<!-- Load Summernote WYSIWYG Editor style sheet -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Page: {{ $page->title }}</h1>
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
                        <h3 class="card-title fw-bold text-secondary">Page Content Details</h3>
                    </div>
                    <form action="{{ route('admin.page.update', $page->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <label class="form-label fw-semibold text-secondary">Page Title *</label>
                                    <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control" placeholder="Page Title" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Page Slug (Read-only)</label>
                                    <input type="text" value="{{ $page->slug }}" class="form-control bg-light" readonly>
                                </div>
                            </div>

                            @if($page->slug == 'about')
                                <!-- Modular Fields for About Us (No raw HTML editor) -->
                                <div class="card card-outline card-primary p-3 mb-4">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-eye"></i> Vision Section</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Vision Title</label>
                                        <input type="text" name="about_vision_title" value="{{ old('about_vision_title', $page->about_vision_title) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Vision Description</label>
                                        <textarea name="about_vision_description" rows="4" class="form-control">{{ old('about_vision_description', $page->about_vision_description) }}</textarea>
                                    </div>
                                </div>

                                <div class="card card-outline card-success p-3 mb-4">
                                    <h5 class="fw-bold text-success mb-3"><i class="bi bi-award"></i> Mission Section</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Mission Title</label>
                                        <input type="text" name="about_mission_title" value="{{ old('about_mission_title', $page->about_mission_title) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Mission Description</label>
                                        <textarea name="about_mission_description" rows="4" class="form-control">{{ old('about_mission_description', $page->about_mission_description) }}</textarea>
                                    </div>
                                </div>

                                <div class="card card-outline card-info p-3 mb-4">
                                    <h5 class="fw-bold text-info mb-3"><i class="bi bi-info-circle"></i> Who We Are Section</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Who We Are Title</label>
                                        <input type="text" name="about_who_we_are_title" value="{{ old('about_who_we_are_title', $page->about_who_we_are_title) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Who We Are Description</label>
                                        <textarea name="about_who_we_are_description" rows="4" class="form-control">{{ old('about_who_we_are_description', $page->about_who_we_are_description) }}</textarea>
                                    </div>
                                </div>
                            @else
                                <!-- Rich Text Editor for terms and privacy pages -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-secondary">Page Content *</label>
                                    <textarea id="editor" name="description" class="form-control">{{ old('description', $page->description) }}</textarea>
                                </div>
                            @endif

                            @if($page->slug == '404')
                                <div class="card card-outline card-info p-3 mb-4">
                                    <h5 class="fw-bold text-info mb-3"><i class="bi bi-image"></i> 404 Page Background Image</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Upload New Image (Recommended: 1920x600 px)</label>
                                        <input type="file" name="image" class="form-control mb-2">
                                        @if(!empty($page->image) && file_exists(public_path('upload/pages/' . $page->image)))
                                            <div class="mt-2">
                                                <label class="form-label d-block fw-semibold text-secondary">Current Image Preview:</label>
                                                <img src="{{ asset('upload/pages/' . $page->image) }}" alt="404 Background" style="max-height: 150px; border-radius: 4px; border: 1px solid #ddd;">
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <label class="form-label d-block fw-semibold text-secondary">Current Default Image:</label>
                                                <img src="{{ asset('assets/images/backgrounds/error-bg.jpg') }}" alt="404 Background Default" style="max-height: 150px; border-radius: 4px; border: 1px solid #ddd;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <hr class="my-4">
                            <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-search"></i> SEO Configuration (Optional)</h5>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="form-control" placeholder="Meta Title">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-control" placeholder="Meta Description">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Meta Keywords</label>
                                <textarea name="meta_keywords" rows="2" class="form-control" placeholder="comma, separated, keywords">{{ old('meta_keywords', $page->meta_keywords) }}</textarea>
                            </div>

                        </div>
                        <div class="card-footer bg-transparent py-3">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold me-2">Save Changes</button>
                            <a href="{{ route('admin.page.list') }}" class="btn btn-secondary px-4 fw-semibold">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<!-- Load jQuery (required for Summernote) and Summernote library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        if ($('#editor').length) {
            $('#editor').summernote({
                placeholder: 'Write page contents here...',
                tabsize: 2,
                height: 350,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    });
</script>
@endsection
