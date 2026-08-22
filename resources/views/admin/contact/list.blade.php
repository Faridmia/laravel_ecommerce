@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Contact Us</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        
        <!-- Contact Us Search Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h3 class="card-title fw-bold text-secondary">Contact Us Search</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contact.list') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-secondary fw-semibold">ID</label>
                            <input type="text" name="id" value="{{ request()->id }}" class="form-control" placeholder="ID">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-secondary fw-semibold">Name</label>
                            <input type="text" name="name" value="{{ request()->name }}" class="form-control" placeholder="Name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary fw-semibold">Email</label>
                            <input type="text" name="email" value="{{ request()->email }}" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-secondary fw-semibold">Phone</label>
                            <input type="text" name="phone" value="{{ request()->phone }}" class="form-control" placeholder="Phone">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary fw-semibold">Subject</label>
                            <input type="text" name="subject" value="{{ request()->subject }}" class="form-control" placeholder="Subject">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold me-2">Search</button>
                            <a href="{{ route('admin.contact.list') }}" class="btn btn-danger px-4 fw-semibold">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <!-- Contact Us List Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Contact Us</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Login Name</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th style="width: 170px">Created Date</th>
                                    <th style="width: 100px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>{{ !empty($value->login_name) ? $value->login_name : '—' }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td><a href="mailto:{{ $value->email }}">{{ $value->email }}</a></td>
                                        <td>{{ !empty($value->phone) ? $value->phone : '—' }}</td>
                                        <td>{{ !empty($value->subject) ? $value->subject : '—' }}</td>
                                        <td>{!! nl2br(e($value->message)) !!}</td>
                                        <td>{{ date('Y-m-d H:i:s', strtotime($value->created_at)) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.contact.delete', $value->id) }}" class="btn btn-sm btn-danger fw-semibold px-3" onclick="return confirm('Are you sure you want to delete this contact message?')">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">No contact messages found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($getRecord->hasPages())
                        <div class="card-footer bg-transparent py-3 clearfix">
                            <div class="float-end">
                                {{ $getRecord->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection
