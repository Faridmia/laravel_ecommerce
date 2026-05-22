@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product List</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ route('admin.product.add') }}" class="btn btn-primary float-sm-right text-right" >Add New Product</a>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Product List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped" role="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Slug</th>
                               <th>Created By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                         @foreach($getRecord as $value)
                        <tbody>
                            <tr class="align-middle">
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->product_title }}</td>
                                <td>{{ $value->slug }}</td>
                                <td>{{ $value->created_by_name }}</td>
                                <td>
                                    {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.product.edit', $value->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('admin.product.delete', $value->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>    
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