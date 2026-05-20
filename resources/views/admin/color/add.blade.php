@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Add New Brand</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8">

                <div class="card card-primary card-outline mb-4">

                    @include('admin.layouts._message')

                    <form action="{{ route('admin.color.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">
                                    Color Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Color Code
                                </label>

                                <input type="color"
                                    name="code"
                                    value="{{ old('code') }}"
                                    class="form-control"
                                    placeholder="#000000">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status" class="form-select">
                                    <option value="0">Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</div>

@endsection