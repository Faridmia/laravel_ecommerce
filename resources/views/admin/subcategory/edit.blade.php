@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Sub Category</h1>
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
                  <form action="{{ route('admin.subcategory.update', $getRecord->id) }}" method="POST">
                   {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <!--begin::Body-->
                    <div class="card-body">
                      <div class="mb-3">
                        <label  class="form-label">Category Name <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select">
                          <option value="">Select Category</option>
                          @foreach($getCategory as $list)
                          <option value="{{ $list->id }}" {{ $getRecord->category_id == $list->id ? 'selected' : '' }}>
                            {{ $list->name }}
                          </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Sub Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="sub_category_name" value="{{ $getRecord->name }}" class="form-control">
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Sub Category Slug <span class="text-danger">*</span></label>
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
                          <option value="0" {{ $getRecord->status == 0 ? 'selected' : '' }}>Active</option>
                          <option value="1" {{ $getRecord->status == 1 ? 'selected' : '' }}>Inactive</option>
                        </select>
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