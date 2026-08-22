@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Product Reviews (Total : {{ $getRecord->total() }})</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        
        <!-- Review Search Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h3 class="card-title fw-bold text-secondary">Review Search</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reviews.list') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary fw-semibold">Product Title</label>
                            <input type="text" name="product_title" value="{{ request()->product_title }}" class="form-control" placeholder="Product Title">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-secondary fw-semibold">Reviewer (Name/Email)</label>
                            <input type="text" name="reviewer" value="{{ request()->reviewer }}" class="form-control" placeholder="Name or Email">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-secondary fw-semibold">Rating</label>
                            <select name="rating" class="form-control">
                                <option value="">Select Rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request()->rating == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-secondary fw-semibold">Status</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="0" {{ request()->status === '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ request()->status === '1' ? 'selected' : '' }}>Approved</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-secondary fw-semibold">Date</label>
                            <input type="date" name="start_date" value="{{ request()->start_date }}" class="form-control mb-2" placeholder="Start Date">
                            <input type="date" name="end_date" value="{{ request()->end_date }}" class="form-control" placeholder="End Date">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold me-2">Search</button>
                            <a href="{{ route('admin.reviews.list') }}" class="btn btn-secondary px-4 fw-semibold">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <!-- Review List Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h3 class="card-title fw-bold text-secondary">Reviews List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped align-middle" role="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Reviewer</th>
                                    <th>Product</th>
                                    <th style="width: 120px; white-space: nowrap;">Rating</th>
                                    <th>Review Text</th>
                                    <th>Created Date</th>
                                    <th style="width: 150px;">Status</th>
                                    <th style="width: 100px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr id="review-row-{{ $value->id }}">
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            <strong>{{ $value->name }}</strong><br>
                                            <small><a href="mailto:{{ $value->email }}">{{ $value->email }}</a></small>
                                        </td>
                                        <td>
                                            @if($value->product)
                                                <a href="{{ url($value->product->slug) }}" target="_blank">
                                                    {{ $value->product->product_title }}
                                                </a>
                                            @else
                                                <span class="text-danger">Product Not Found</span>
                                            @endif
                                        </td>
                                        <td style="white-space: nowrap;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill {{ $i <= $value->rating ? 'text-warning' : 'text-secondary' }}" style="font-size: 1.2rem;"></i>
                                            @endfor
                                        </td>
                                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: normal;">
                                            {{ $value->review }}
                                        </td>
                                        <td>{{ date('d-m-Y h:i A', strtotime($value->created_at)) }}</td>
                                        <td>
                                            <select class="form-select form-select-sm review-status-select" data-id="{{ $value->id }}">
                                                <option value="0" {{ $value->status == 0 ? 'selected' : '' }}>Pending</option>
                                                <option value="1" {{ $value->status == 1 ? 'selected' : '' }}>Approved</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.reviews.delete', $value->id) }}" class="btn btn-sm btn-danger fw-semibold px-3" onclick="return confirm('Are you sure you want to delete this review?')">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-secondary fw-semibold">No reviews found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-transparent py-3">
                        <div class="d-flex justify-content-end">
                            {!! $getRecord->appends(request()->except('page'))->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle inline review status changes via fetch API
        document.querySelectorAll('.review-status-select').forEach(function (selectEl) {
            selectEl.addEventListener('change', function () {
                const reviewId = this.getAttribute('data-id');
                const newStatus = this.value;

                selectEl.disabled = true;

                fetch("{{ route('admin.reviews.update_status') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSR-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: reviewId,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    selectEl.disabled = false;
                    if (data.status) {
                        // Flash message in status wrapper or notify
                        const row = document.getElementById(`review-row-${reviewId}`);
                        row.style.transition = 'background-color 0.5s ease';
                        row.style.backgroundColor = '#d4edda';
                        setTimeout(() => {
                            row.style.backgroundColor = '';
                        }, 1000);
                    } else {
                        alert('Failed to update status. Please try again.');
                    }
                })
                .catch(error => {
                    selectEl.disabled = false;
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });
    });
</script>
@endsection
