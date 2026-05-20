@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Color</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8">

                <div class="card card-primary card-outline mb-4">

                    @include('admin.layouts._message')

                   <form action="{{ route('admin.color.update', $getRecord->color_id) }}" method="POST">

                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">
                                    Color Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="name"
                                    value="{{ $getRecord->name }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Color Code
                                </label>

                                <input type="text"
                                    name="code"
                                    value="{{ $getRecord->code }}"
                                    class="form-control"
                                    placeholder="#000000">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status" class="form-select">

                                    <option value="0"
                                        {{ $getRecord->status == 0 ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="1"
                                        {{ $getRecord->status == 1 ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</div>

@endsection