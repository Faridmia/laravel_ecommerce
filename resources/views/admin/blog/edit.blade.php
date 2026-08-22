@extends('admin.layouts.app')
@section('content')

<!-- summernote css -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Blog</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="card card-primary card-outline mb-4">
                    @include('admin.layouts._message')
                    <form action="{{ route('admin.blog.update', $getRecord->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ $getRecord->title }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="blog_category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($getCategory as $category)
                                        <option value="{{ $category->id }}" {{ $getRecord->blog_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted d-block mb-2">Leave empty to keep the current image.</small>
                                <div class="mt-2">
                                    <label class="form-label d-block fw-semibold text-secondary">Current Image Preview:</label>
                                    <img src="{{ $getRecord->getImageUrl() }}" alt="Blog Image" style="max-height: 120px; border-radius: 4px; border: 1px solid #ddd;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="3">{{ $getRecord->short_description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tags</label>
                                <input type="text" name="tags" value="{{ $getRecord->tags }}" placeholder="Furniture, Decor, Lighting" class="form-control">
                                <small class="text-muted">Enter tags separated by commas (e.g. "Furniture, Decor").</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Description</label>
                                <textarea name="description" id="editor" class="form-control" rows="8">{{ $getRecord->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option {{ $getRecord->status == 0 ? 'selected' : '' }} value="0">Active</option>
                                    <option {{ $getRecord->status == 1 ? 'selected' : '' }} value="1">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- summernote js dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#editor').summernote({
            placeholder: 'Write blog content here...',
            tabsize: 2,
            height: 300,
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
    });
</script>

@endsection
