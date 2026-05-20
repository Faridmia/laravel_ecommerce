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

                    <form action="{{ route('admin.brand.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">
                                    Brand Name <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Brand Slug <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="slug"
                                    value="{{ old('slug') }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description"
                                    class="form-control"
                                    rows="5">{{ old('description') }}</textarea>
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