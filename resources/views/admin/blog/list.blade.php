@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Blogs</h1>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.blog.add') }}" class="btn btn-primary fw-semibold"><i class="bi bi-plus-lg"></i> Add New Blog</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <!-- Blogs List Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Blogs List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th style="width: 120px">Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th style="width: 180px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            <img src="{{ $value->getImageUrl() }}" alt="blog image" class="border bg-light p-1" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td>
                                        <td><strong>{{ $value->title }}</strong></td>
                                        <td>{{ $value->blogCategory->name ?? 'N/A' }}</td>
                                        <td>{{ $value->author->name ?? 'Admin' }}</td>
                                        <td>
                                            <span class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $value->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.blog.edit', $value->id) }}" class="btn btn-sm btn-primary fw-semibold px-3 me-1">Edit</a>
                                            <a href="{{ route('admin.blog.delete', $value->id) }}" class="btn btn-sm btn-danger fw-semibold px-3" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-secondary">No blogs added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card-footer clearfix bg-transparent">
                            {{ $getRecord->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
