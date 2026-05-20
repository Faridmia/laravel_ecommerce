@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Brand List</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ route('admin.brand.add') }}" class="btn btn-primary float-sm-right text-right" >Add Brand</a>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Brand List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped" role="table">
                        <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Brand Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($getRecord as $value)
                                <tr class="align-middle">

                                    <td>{{ $value->brand_id }}</td>

                                    <td>{{ $value->name }}</td>

                                    <td>{{ $value->slug }}</td>

                                    <td>{{ $value->description }}</td>

                                    <td>
                                        {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.brand.edit', $value->brand_id) }}"
                                            class="btn btn-sm btn-primary">
                                            Edit
                                        </a>

                                        <a href="{{ route('admin.brand.delete', $value->brand_id) }}"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this brand?')">
                                            Delete
                                        </a>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer clearfix">
                            {{ $getRecord->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</div>

@endsection