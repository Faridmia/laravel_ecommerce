@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Color List</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ route('admin.color.add') }}" class="btn btn-primary float-sm-right text-right" >Add Color</a>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Color List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped" role="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Color Name</th>
                                    <th>Color Code</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($getRecord as $value)
                                <tr class="align-middle">

                                    <td>{{ $value->color_id }}</td>

                                    <td>{{ $value->name }}</td>

                                    <td>{{ $value->code }}</td>

                                    <td>{{ $value->created_by_name }}</td>

                                    <td>
                                        {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                                    </td>

                                    <td>
                                        {{ $value->created_at }}
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.color.edit', $value->color_id) }}"
                                            class="btn btn-sm btn-primary">
                                            Edit
                                        </a>

                                        <a href="{{ route('admin.color.delete', $value->color_id) }}"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this color?')">
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