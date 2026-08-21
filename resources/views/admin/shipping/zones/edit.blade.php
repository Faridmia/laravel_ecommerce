@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Configure Shipping Zone: {{ $getRecord->name }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.shipping.zones.list') }}" class="btn btn-default float-sm-right">Back to List</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        @include('admin.layouts._message')

        <div class="row">
            <!-- Left Column: Zone Info & Locations -->
            <div class="col-lg-5">
                <!-- Zone Settings -->
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Zone Details</h3>
                    </div>
                    <form action="{{ route('admin.shipping.zones.update', $getRecord->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Zone Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $getRecord->name) }}" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-select" required>
                                    <option value="1" {{ $getRecord->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $getRecord->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Details</button>
                        </div>
                    </form>
                </div>

                <!-- Zone Locations -->
                <div class="card card-info card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Zone Regions / Locations</h3>
                    </div>
                    <div class="card-body">
                        <!-- List Locations -->
                        <h5>Assigned Regions</h5>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Region Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord->locations as $loc)
                                        <tr>
                                            <td>
                                                <strong>
                                                    @if($loc->area)
                                                        {{ $loc->area->name }} (Area) &raquo;
                                                    @endif
                                                    @if($loc->district)
                                                        {{ $loc->district->name }} (District) &raquo;
                                                    @endif
                                                    @if($loc->division)
                                                        {{ $loc->division->name }} (Division) &raquo;
                                                    @endif
                                                    {{ $loc->country->name }} (Country)
                                                </strong>
                                            </td>
                                            <td style="width: 80px;">
                                                <a href="{{ route('admin.shipping.locations.delete', $loc->id) }}" class="btn btn-xs btn-danger" onclick="return confirm('Remove this location?')">Remove</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No locations assigned yet. This zone applies everywhere (worldwide).</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <!-- Add Location Form -->
                        <h5>Add Region to Zone</h5>
                        <form action="{{ route('admin.shipping.locations.store', $getRecord->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select name="country_id" id="loc_country_id" class="form-select" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}" data-code="{{ $c->code }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="division_wrapper">
                                <label class="form-label">State / Division (Optional)</label>
                                <select name="division_id" id="loc_division_id" class="form-select">
                                    <option value="">All Divisions</option>
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="district_wrapper">
                                <label class="form-label">City / District (Optional)</label>
                                <select name="district_id" id="loc_district_id" class="form-select">
                                    <option value="">All Districts</option>
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="area_wrapper">
                                <label class="form-label">Area / Thana (Optional)</label>
                                <select name="area_id" id="loc_area_id" class="form-select">
                                    <option value="">All Areas</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-info text-white">Add Region</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Shipping Methods & Rates -->
            <div class="col-lg-7">
                <!-- Add Shipping Method -->
                <div class="card card-success card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Add Shipping Method</h3>
                    </div>
                    <form action="{{ route('admin.shipping.methods.store', $getRecord->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Method Name <span class="text-danger">*</span></label>
                                    <input type="text" name="method_name" placeholder="e.g. Flat Rate Shipping, Free Delivery" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Method Type <span class="text-danger">*</span></label>
                                    <select name="method_type" class="form-select" required>
                                        <option value="flat_rate">Flat Rate</option>
                                        <option value="free_shipping">Free Shipping</option>
                                        <option value="local_pickup">Local Pickup</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Add Shipping Method</button>
                        </div>
                    </form>
                </div>

                <!-- List Methods and configuration -->
                @foreach($getRecord->methods as $method)
                    <div class="card card-secondary card-outline mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <strong>{{ $method->name }}</strong> 
                                <span class="badge bg-secondary ml-2 mr-2" style="margin-left: 10px; margin-right: 10px;">{{ ucfirst(str_replace('_', ' ', $method->type)) }}</span>
                            </h4>
                            
                            <!-- Inline Rename Form -->
                            <form action="{{ route('admin.shipping.methods.update_form', $method->id) }}" method="POST" class="d-flex align-items-center" style="margin-left: 15px;">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $method->name }}" class="form-control form-control-sm" style="width: 150px; height: 26px; padding: 2px 5px;" required>
                                <button type="submit" class="btn btn-xs btn-outline-success" style="margin-left: 5px; height: 26px; padding: 2px 8px;">Rename</button>
                            </form>

                            <div style="margin-left: auto;">
                                <a href="{{ route('admin.shipping.methods.delete', $method->id) }}" class="btn btn-xs btn-danger" onclick="return confirm('Delete this shipping method and all its rates?')">Delete Method</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Rates Table -->
                            <h5>Rates &amp; Criteria</h5>
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Cost / Charge</th>
                                            <th>Min Order Required</th>
                                            <th>Weight Range (kg)</th>
                                            <th>Estimated Delivery</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($method->rates as $rate)
                                            <tr id="rate-row-{{ $rate->id }}">
                                                <td>
                                                    @if($rate->free_shipping)
                                                        <span class="badge bg-success">Free Shipping</span>
                                                    @else
                                                        ${{ number_format($rate->charge, 2) }}
                                                    @endif
                                                </td>
                                                <td>${{ number_format($rate->min_order_amount, 2) }}</td>
                                                <td>
                                                    @if($rate->min_weight !== null || $rate->max_weight !== null)
                                                        {{ $rate->min_weight ?? '0' }} - {{ $rate->max_weight ?? '&infin;' }}
                                                    @else
                                                        Any Weight
                                                    @endif
                                                </td>
                                                <td>{{ $rate->estimated_days ?: 'N/A' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary edit-rate-btn" data-id="{{ $rate->id }}">Edit</button>
                                                    <a href="{{ route('admin.shipping.rates.delete', $rate->id) }}" class="btn btn-xs btn-danger" onclick="return confirm('Delete this rate tier?')">Delete</a>
                                                </td>
                                            </tr>

                                            <!-- Inline Rate Edit Row -->
                                            <tr id="rate-edit-row-{{ $rate->id }}" class="d-none bg-light">
                                                <td>
                                                    @if($rate->free_shipping)
                                                        <input type="hidden" name="charge" value="0" form="edit-rate-form-{{ $rate->id }}">
                                                        <span class="badge bg-success">Free Shipping</span>
                                                    @else
                                                        <input type="number" step="0.01" name="charge" value="{{ $rate->charge }}" class="form-control form-control-sm" required style="max-width: 100px;" form="edit-rate-form-{{ $rate->id }}">
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="min_order_amount" value="{{ $rate->min_order_amount }}" class="form-control form-control-sm" style="max-width: 100px;" form="edit-rate-form-{{ $rate->id }}">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <input type="number" step="0.01" name="min_weight" value="{{ $rate->min_weight }}" placeholder="Min" class="form-control form-control-sm" style="width: 70px;" form="edit-rate-form-{{ $rate->id }}">
                                                        <span style="margin: 0 5px;">-</span>
                                                        <input type="number" step="0.01" name="max_weight" value="{{ $rate->max_weight }}" placeholder="Max" class="form-control form-control-sm" style="width: 70px;" form="edit-rate-form-{{ $rate->id }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="estimated_days" value="{{ $rate->estimated_days }}" class="form-control form-control-sm" form="edit-rate-form-{{ $rate->id }}">
                                                </td>
                                                <td>
                                                    <form id="edit-rate-form-{{ $rate->id }}" action="{{ route('admin.shipping.rates.update', $rate->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-xs btn-success">Save</button>
                                                        <button type="button" class="btn btn-xs btn-secondary cancel-rate-edit" data-id="{{ $rate->id }}">Cancel</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No rate rules added. This method is inactive.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <!-- Add Rate Rule -->
                            <h6 class="font-weight-bold">Add Rate Criteria / Weight Tier</h6>
                            <form action="{{ route('admin.shipping.rates.store', $method->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label small">Cost / Charge ($)</label>
                                        <input type="number" step="0.01" name="charge" value="{{ $method->type == 'free_shipping' ? '0' : '50' }}" class="form-control form-control-sm" {{ $method->type == 'free_shipping' ? 'readonly' : '' }} required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label small">Min Order ($)</label>
                                        <input type="number" step="0.01" name="min_order_amount" placeholder="0" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label small">Min Weight (kg)</label>
                                        <input type="number" step="0.01" name="min_weight" placeholder="0" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label small">Max Weight (kg)</label>
                                        <input type="number" step="0.01" name="max_weight" placeholder="No Limit" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label small">Estimated Delivery Days</label>
                                        <input type="text" name="estimated_days" placeholder="e.g. 2-3 Days" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-8 mb-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-secondary btn-sm btn-block">Save Rate Rule</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Country Change
        $('#loc_country_id').on('change', function() {
            let countryId = $(this).val();
            let countryCode = $(this).find(':selected').data('code');
            
            // Clear and hide dependent elements
            $('#loc_division_id').html('<option value="">All Divisions</option>');
            $('#loc_district_id').html('<option value="">All Districts</option>');
            $('#loc_area_id').html('<option value="">All Areas</option>');
            
            $('#division_wrapper').addClass('d-none');
            $('#district_wrapper').addClass('d-none');
            $('#area_wrapper').addClass('d-none');
            
            if (countryCode === 'BD') { // Bangladesh
                $.ajax({
                    url: "{{ url('locations/divisions') }}/" + countryId,
                    method: 'GET',
                    success: function(data) {
                        if (data && data.length > 0) {
                            let options = '<option value="">All Divisions</option>';
                            data.forEach(function(item) {
                                options += `<option value="${item.id}">${item.name}</option>`;
                            });
                            $('#loc_division_id').html(options);
                            $('#division_wrapper').removeClass('d-none');
                        }
                    }
                });
            }
        });

        // Division Change
        $('#loc_division_id').on('change', function() {
            let divisionId = $(this).val();
            
            // Clear and hide dependent elements
            $('#loc_district_id').html('<option value="">All Districts</option>');
            $('#loc_area_id').html('<option value="">All Areas</option>');
            
            $('#district_wrapper').addClass('d-none');
            $('#area_wrapper').addClass('d-none');
            
            if (divisionId) {
                $.ajax({
                    url: "{{ url('locations/districts') }}/" + divisionId,
                    method: 'GET',
                    success: function(data) {
                        if (data && data.length > 0) {
                            let options = '<option value="">All Districts</option>';
                            data.forEach(function(item) {
                                options += `<option value="${item.id}">${item.name}</option>`;
                            });
                            $('#loc_district_id').html(options);
                            $('#district_wrapper').removeClass('d-none');
                        }
                    }
                });
            }
        });

        // District Change
        $('#loc_district_id').on('change', function() {
            let districtId = $(this).val();
            
            // Clear and hide dependent elements
            $('#loc_area_id').html('<option value="">All Areas</option>');
            $('#area_wrapper').addClass('d-none');
            
            if (districtId) {
                $.ajax({
                    url: "{{ url('locations/areas') }}/" + districtId,
                    method: 'GET',
                    success: function(data) {
                        if (data && data.length > 0) {
                            let options = '<option value="">All Areas</option>';
                            data.forEach(function(item) {
                                options += `<option value="${item.id}">${item.name}</option>`;
                            });
                            $('#loc_area_id').html(options);
                            $('#area_wrapper').removeClass('d-none');
                        }
                    }
                });
            }
        });

        // Toggle Rate Edit Row
        $(document).on('click', '.edit-rate-btn', function() {
            let id = $(this).data('id');
            $('#rate-row-' + id).addClass('d-none');
            $('#rate-edit-row-' + id).removeClass('d-none');
        });

        $(document).on('click', '.cancel-rate-edit', function() {
            let id = $(this).data('id');
            $('#rate-edit-row-' + id).addClass('d-none');
            $('#rate-row-' + id).removeClass('d-none');
        });
    });
</script>
@endsection
