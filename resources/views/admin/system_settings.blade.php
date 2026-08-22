@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>System Setting</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="container-fluid">
        <!-- Success and Error Messages -->
        @include('admin.layouts._message')

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline mb-4 shadow-sm">
                    <form action="{{ route('admin.system_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body px-4 py-3">
                            
                            <!-- Website Name -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Website <span class="text-danger">*</span></label>
                                <input type="text" name="website_name" class="form-control form-control-lg" value="{{ old('website_name', $settings->website_name) }}" required placeholder="Enter website name">
                            </div>

                            <hr class="my-4">

                            <!-- Logo Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Logo</label>
                                <input type="file" name="logo" class="form-control" id="logoInput" accept="image/*">
                                <div class="mt-3">
                                    <img id="logoPreview" src="{{ $settings->getLogoUrl() }}" alt="Logo Preview" class="img-thumbnail" style="max-height: 80px; object-fit: contain; background: #f8f9fa;">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Favicon Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Fevicon</label>
                                <input type="file" name="fevicon" class="form-control" id="feviconInput" accept="image/*">
                                <div class="mt-3">
                                    <img id="feviconPreview" src="{{ $settings->getFaviconUrl() }}" alt="Favicon Preview" class="img-thumbnail" style="max-height: 40px; object-fit: contain; background: #f8f9fa;">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Footer Description -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Footer Description</label>
                                <textarea name="footer_description" class="form-control" rows="4" placeholder="Enter footer description">{{ old('footer_description', $settings->footer_description) }}</textarea>
                            </div>

                            <hr class="my-4">

                            <!-- Footer Payment Icon Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Footer Payment Icon</label>
                                <input type="file" name="footer_payment_icon" class="form-control" id="paymentIconInput" accept="image/*">
                                <div class="mt-3">
                                    <img id="paymentIconPreview" src="{{ $settings->getPaymentIconUrl() }}" alt="Payment Icon Preview" class="img-thumbnail" style="max-height: 50px; object-fit: contain; background: #f8f9fa;">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" class="form-control" rows="4" placeholder="Enter physical address">{{ old('address', $settings->address) }}</textarea>
                            </div>

                            <hr class="my-4">

                            <!-- Phone and Phone 2 -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}" placeholder="Enter phone number">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Phone 2</label>
                                    <input type="text" name="phone_two" class="form-control" value="{{ old('phone_two', $settings->phone_two) }}" placeholder="Enter secondary phone number">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Submit Contact Email -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Submit Contact Email</label>
                                <input type="email" name="submit_email" class="form-control" value="{{ old('submit_email', $settings->submit_email) }}" placeholder="Enter contact form destination email">
                            </div>

                            <hr class="my-4">

                            <!-- Email and Email 2 -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}" placeholder="Enter contact email">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Email 2</label>
                                    <input type="email" name="email_two" class="form-control" value="{{ old('email_two', $settings->email_two) }}" placeholder="Enter secondary contact email">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Working Hour -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Working Hour</label>
                                <textarea name="working_hour" class="form-control" rows="6" placeholder="Monday-Saturday&#10;11am-7pm ET&#10;&#10;Sunday&#10;11am-6pm ET">{{ old('working_hour', $settings->working_hour) }}</textarea>
                                <small class="text-muted">
                                    Format: Enter weekday title and hours on separate lines, leave a blank line, and then enter weekend title and hours. E.g.:
                                    <br><strong>Monday-Saturday</strong>
                                    <br><strong>11am-7pm ET</strong>
                                    <br><em>[Leave a blank line here]</em>
                                    <br><strong>Sunday</strong>
                                    <br><strong>11am-6pm ET</strong>
                                </small>
                            </div>

                            <hr class="my-4">

                            <!-- Social Links -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Facebook Link</label>
                                <input type="text" name="facebook_link" class="form-control" value="{{ old('facebook_link', $settings->facebook_link) }}" placeholder="Enter Facebook page link">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Twitter Link</label>
                                <input type="text" name="twitter_link" class="form-control" value="{{ old('twitter_link', $settings->twitter_link) }}" placeholder="Enter Twitter profile link">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Instagram Link</label>
                                <input type="text" name="instagram_link" class="form-control" value="{{ old('instagram_link', $settings->instagram_link) }}" placeholder="Enter Instagram profile link">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Youtube Link</label>
                                <input type="text" name="youtube_link" class="form-control" value="{{ old('youtube_link', $settings->youtube_link) }}" placeholder="Enter Youtube channel link">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Pinterest Link</label>
                                <input type="text" name="pinterest_link" class="form-control" value="{{ old('pinterest_link', $settings->pinterest_link) }}" placeholder="Enter Pinterest profile link">
                            </div>

                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-top-0 pb-4 px-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5 fw-semibold">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Live image previews
    function setupImagePreview(inputId, previewId) {
        document.getElementById(inputId).addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    setupImagePreview('logoInput', 'logoPreview');
    setupImagePreview('feviconInput', 'feviconPreview');
    setupImagePreview('paymentIconInput', 'paymentIconPreview');
</script>
@endsection
