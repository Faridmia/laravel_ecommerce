@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sub Category List</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ route('admin.subcategory.add') }}" class="btn btn-primary float-sm-right text-right" >Add Sub Category</a>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Sub Category List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped" role="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sub Category Name</th>
                                <th>Category Name</th>
                                <th>Category Slug</th>
                                <th>Meta Title</th>
                                <th>Meta Description</th>
                                <th>Meta Keywords</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getRecord as $value)
                            <tr class="align-middle">
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->category->name }}</td>
                                <td>{{ $value->category_slug }}</td>
                                <td>{{ $value->meta_title }}</td>
                                <td>{{ $value->meta_description }}</td>
                                <td>{{ $value->meta_keywords }}</td>
                                <td>
                                    {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.subcategory.edit', $value->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route('admin.subcategory.delete', $value->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this subcategory?')">Delete</a>    
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