@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Add New Shipping Zone</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline mb-4">
                    <form action="{{ route('admin.shipping.zones.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Zone Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Inside Dhaka, Outside Dhaka" class="form-control" required>
                                <small class="text-muted">Give this zone a clear name describing the region it covers.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-select" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Create Zone &amp; Continue</button>
                            <a href="{{ route('admin.shipping.zones.list') }}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
