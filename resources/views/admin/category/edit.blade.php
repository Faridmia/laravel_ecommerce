@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Category</h1>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-md-8">
                <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4">
                  <!--begin::Form-->
                  @include('admin.layouts._message')
                  
                  <form action="{{ route('admin.category.update', $getRecord->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <!--begin::Body-->
                    <div class="card-body">
                    <div class="mb-3">
                        <label  class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ $getRecord->name }}" class="form-control">
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Category Slug <span class="text-danger">*</span></label>
                        <input type="text" name="category_slug" value="{{ $getRecord->category_slug }}" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label  class="form-label">Meta Title <span class="text-danger">*</span></label>
                        <input type="text" name="meta_title" value="{{ $getRecord->meta_title }}" class="form-control">
                      </div>
                      
                      <div class="mb-3">
                        <label  class="form-label">Meta Description</label>
                        <textarea name="meta_description" id="" class="form-control">{{ $getRecord->meta_description }}</textarea>
                      </div>
              
                       <div class="mb-3">
                        <label  class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ $getRecord->meta_keywords }}" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label  class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select">
                          <option {{ $getRecord->status == 0 ? 'selected' : '' }} value="0">Active</option>
                          <option {{ $getRecord->status == 1 ? 'selected' : '' }} value="1">Inactive</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Show on Homepage Trendy Tabs?</label>
                        <select name="is_home" class="form-select">
                          <option {{ $getRecord->is_home == 0 ? 'selected' : '' }} value="0">No</option>
                          <option {{ $getRecord->is_home == 1 ? 'selected' : '' }} value="1">Yes</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Category Banner Image</label>
                        <input type="file" name="image" class="form-control">
                        <small class="text-muted d-block mb-2">Upload a banner image to be displayed on the homepage categories section. Leave empty to keep the current image.</small>
                        @if(!empty($getRecord->image) && file_exists(public_path('upload/categories/' . $getRecord->image)))
                            <div class="mt-2">
                                <label class="form-label d-block fw-semibold text-secondary">Current Banner Preview:</label>
                                <img src="{{ asset('upload/categories/' . $getRecord->image) }}" alt="Category Banner" style="max-height: 100px; border-radius: 4px; border: 1px solid #ddd;">
                            </div>
                        @else
                            <div class="mt-2">
                                <label class="form-label d-block fw-semibold text-secondary">Current Default Banner:</label>
                                <img src="{{ $getRecord->getImageUrl() }}" alt="Category Banner Default" style="max-height: 100px; border-radius: 4px; border: 1px solid #ddd;">
                            </div>
                        @endif
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" value="{{ $getRecord->button_text }}" placeholder="Shop Now" class="form-control">
                        <small class="text-muted">Text for the call-to-action button (e.g. "Shop Now", "Explore"). Default is "Shop Now".</small>
                      </div>
                     
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
                </div>
                <!--end::Quick Example-->
              </div>
        </div>
       
    </div>
</div>

@endsection