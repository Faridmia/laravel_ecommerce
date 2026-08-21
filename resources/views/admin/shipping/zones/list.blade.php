@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Zones</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ route('admin.shipping.zones.add') }}" class="btn btn-primary float-sm-right text-right">Add New Zone</a>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container-fluid">
        @include('admin.layouts._message')
        
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Shipping Zones List</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped" role="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Zone Name</th>
                                    <th>Zone Locations / Region</th>
                                    <th>Shipping Methods</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                <tr class="align-middle">
                                    <td>{{ $value->id }}</td>
                                    <td><strong>{{ $value->name }}</strong></td>
                                    <td>
                                        @php
                                            $locs = [];
                                            foreach($value->locations as $loc) {
                                                $desc = '';
                                                if ($loc->area) {
                                                    $desc .= $loc->area->name . ' (Area), ';
                                                }
                                                if ($loc->district) {
                                                    $desc .= $loc->district->name . ' (District), ';
                                                }
                                                if ($loc->division) {
                                                    $desc .= $loc->division->name . ' (Division), ';
                                                }
                                                if ($loc->country) {
                                                    $desc .= $loc->country->name;
                                                }
                                                $locs[] = rtrim($desc, ', ');
                                            }
                                        @endphp
                                        @if(empty($locs))
                                            <span class="text-muted">Everywhere (No location restrictions)</span>
                                        @else
                                            {!! implode('<br><span class="text-secondary">&bull;</span> ', $locs) !!}
                                        @endif
                                    </td>
                                    <td>
                                        @forelse($value->methods as $method)
                                            <span class="badge bg-info mb-1">{{ $method->name }} ({{ ucfirst(str_replace('_', ' ', $method->type)) }})</span>
                                        @empty
                                            <span class="text-muted">No methods configured</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $value->is_active == 1 ? 'success' : 'danger' }}">
                                            {{ $value->is_active == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.shipping.zones.edit', $value->id) }}" class="btn btn-sm btn-primary">
                                            Configure / Edit
                                        </a>
                                        <a href="{{ route('admin.shipping.zones.delete', $value->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this zone?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4">No shipping zones found. Please create one to set up shipping.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card-footer clearfix">
                            {{ $getRecord->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
