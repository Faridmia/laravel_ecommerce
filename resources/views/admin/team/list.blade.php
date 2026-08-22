@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Team Members</h1>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.team.add') }}" class="btn btn-primary fw-semibold"><i class="bi bi-plus-lg"></i> Add New Member</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <!-- Team Members List Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Members list</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th style="width: 80px">Photo</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Social Links</th>
                                    <th style="width: 180px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            <img src="{{ $value->getImageUrl() }}" alt="member image" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td><strong>{{ $value->name }}</strong></td>
                                        <td>{{ $value->designation }}</td>
                                        <td>
                                            @if($value->facebook_link) <a href="{{ $value->facebook_link }}" target="_blank" class="me-2 text-primary"><i class="bi bi-facebook"></i></a> @endif
                                            @if($value->twitter_link) <a href="{{ $value->twitter_link }}" target="_blank" class="me-2 text-info"><i class="bi bi-twitter"></i></a> @endif
                                            @if($value->instagram_link) <a href="{{ $value->instagram_link }}" target="_blank" class="text-danger"><i class="bi bi-instagram"></i></a> @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.team.edit', $value->id) }}" class="btn btn-sm btn-primary fw-semibold px-3 me-1">Edit</a>
                                            <a href="{{ route('admin.team.delete', $value->id) }}" class="btn btn-sm btn-danger fw-semibold px-3" onclick="return confirm('Are you sure you want to delete this team member?')">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-secondary">No team members added.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
