@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sliders</h1>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.slider.add') }}" class="btn btn-primary fw-semibold"><i class="bi bi-plus-lg"></i> Add New Slider</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <!-- Sliders List Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Sliders List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th style="width: 150px">Image</th>
                                    <th>Subtitle</th>
                                    <th>Title</th>
                                    <th>Button Text</th>
                                    <th>Button Link</th>
                                    <th style="width: 180px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            <img src="{{ $value->getImageUrl() }}" alt="slider image" class="rounded border" style="width: 120px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>{{ $value->subtitle }}</td>
                                        <td><strong>{!! $value->title !!}</strong></td>
                                        <td>{{ $value->button_text }}</td>
                                        <td>{{ $value->button_link }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.slider.edit', $value->id) }}" class="btn btn-sm btn-primary fw-semibold px-3 me-1">Edit</a>
                                            <a href="{{ route('admin.slider.delete', $value->id) }}" class="btn btn-sm btn-danger fw-semibold px-3" onclick="return confirm('Are you sure you want to delete this slider?')">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-secondary">No sliders added yet.</td>
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
