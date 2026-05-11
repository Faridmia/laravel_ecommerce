@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Admin</h1>
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
                  <form action="{{ route('admin.admin.update', $getRecord->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <!--begin::Body-->
                    <div class="card-body">
                    <div class="mb-3">
                        <label  class="form-label">Name</label>
                        <input type="text" name="name" readonly class="form-control" value="{{ $getRecord->name }}">
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" value="{{ $getRecord->email }}">
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Password</label>
                        <input type="text" name="password" class="form-control">
                        <p> Leave blank if you don't want to change the password</p>
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Status</label>
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