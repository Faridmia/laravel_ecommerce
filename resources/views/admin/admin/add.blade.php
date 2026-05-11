@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Add New Admin</h1>
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
                  <form action="{{ route('admin.admin.store') }}" method="POST">
                    {{ csrf_field() }}
                    <!--begin::Body-->
                    <div class="card-body">
                    <div class="mb-3">
                        <label  class="form-label">Name</label>
                        <input type="text" name="name" class="form-control">
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control">
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                      </div>
                      <div class="mb-3">
                        <label  class="form-label">Status</label>
                        <select name="status" class="form-select">
                          <option value="0">Active</option>
                          <option value="1">Inactive</option>
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