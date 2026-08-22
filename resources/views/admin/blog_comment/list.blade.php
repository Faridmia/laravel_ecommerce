@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Blog Comments</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <!-- Comments List Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Comments List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Blog Post</th>
                                    <th>User Info</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th style="width: 250px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            @if($value->blog)
                                                <a href="{{ url('blog/' . $value->blog->slug) }}" target="_blank"><strong>{{ Str::limit($value->blog->title, 40) }}</strong></a>
                                            @else
                                                <span class="text-muted">N/A (Deleted Post)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $value->name }}</strong><br>
                                            <small class="text-secondary">{{ $value->email }}</small>
                                        </td>
                                        <td>{{ $value->comment }}</td>
                                        <td>
                                            @if($value->status == 0)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($value->status == 1)
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($value->status == 2)
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $value->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            @if($value->status == 0 || $value->status == 2)
                                                <a href="{{ route('admin.blog_comment.update_status', [$value->id, 1]) }}" class="btn btn-sm btn-success fw-semibold px-2 me-1" title="Approve">Approve</a>
                                            @endif
                                            @if($value->status == 0 || $value->status == 1)
                                                <a href="{{ route('admin.blog_comment.update_status', [$value->id, 2]) }}" class="btn btn-sm btn-warning fw-semibold px-2 me-1 text-dark" title="Reject">Reject</a>
                                            @endif
                                            <a href="{{ route('admin.blog_comment.delete', $value->id) }}" class="btn btn-sm btn-danger fw-semibold px-2" onclick="return confirm('Are you sure you want to delete this comment?')" title="Delete">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-secondary">No comments found.</td>
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
