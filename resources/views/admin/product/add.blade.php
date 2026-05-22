@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Add New Product</h1>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-md-12">
                <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4">
                  <!--begin::Form-->
                  @include('admin.layouts._message')
                  <form action="{{ route('admin.product.store') }}" method="POST">
                        {{ csrf_field() }}

                        <!--begin::Body-->
                        <div class="card-body">

                            <div class="row">

                                <!-- Product Title -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Product Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="product_title"
                                        value="{{ old('product_title') }}"
                                        class="form-control">
                                </div>

                                <!-- Slug -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug"
                                        value="{{ old('slug') }}"
                                        class="form-control">
                                </div>

                                <!-- SKU -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="sku"
                                        value="{{ old('sku') }}"
                                        class="form-control">
                                </div>

                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" id="changeproductcategory">
                                        <option value="">Select Category</option>

                                        @foreach($categories as $category)
                                             <option value="{{ $category->id }}">
                                                {{ $category->name }}   
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sub Category -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sub Category</label>
                                    <select name="sub_category_id" class="form-select" id="changeproductsubcategory">
                                        <option value="">Select Sub Category</option>

                                       
                                            <option value="">
                                               
                                            </option>
                                       
                                    </select>
                                </div>

                                <!-- Brand -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-select">
                                        <option value="">Select Brand</option>

                                       
                                            <option value="">
                                               
                                            </option>
                                       
                                    </select>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Price <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"  name="price"
                                        value="{{ old('price') }}"
                                        class="form-control">
                                </div>

                                <!-- Sale Price -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price</label>
                                    <input type="number"  name="sale_price"
                                        value="{{ old('sale_price') }}"
                                        class="form-control">
                                </div>
                                

                                

                            </div>
                            <div class="row">
                                <!-- Colors -->
                                <div class="col-md-12 mb-4">

                                    <label class="form-label d-block">
                                        Select Colors
                                    </label>

                                    <div class="d-flex flex-wrap gap-3">

                                       

                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="color_id[]"
                                                    value=""
                                                    id="color">

                                                <label class="form-check-label"
                                                    for="color">

                                                   Red

                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="color_id[]"
                                                    value=""
                                                    id="color">

                                                <label class="form-check-label"
                                                    for="color">

                                                   Black

                                                </label>
                                            </div>

                                      

                                    </div>

                                </div>
                                <div class="col-md-12 mb-4">

                                    <label class="form-label d-block form-group">
                                        Select Sizes <span style="color: red;">*</span>
                                    </label>
                                    <div>
                                        <table class="table table-bordered size-table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="size_name[]"
                                                            class="form-control" placeholder="Size Name">
                                                    </td>
                                                    <td>
                                                        <input type="text**/" name="size_price[]"
                                                            class="form-control" placeholder="Size Price">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-success add-size-row">
                                                            Add
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger remove-size-row">
                                                            Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            
                                        </table>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description"
                                        class="form-control"
                                        rows="5">{{ old('description') }}</textarea>
                                </div>

                                <!-- Short Description -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="short_description"
                                        class="form-control"
                                        rows="4">{{ old('short_description') }}</textarea>
                                </div>

                                <!-- Additional Information -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Additional Information</label>
                                    <textarea name="additional_information"
                                        class="form-control"
                                        rows="4">{{ old('additional_information') }}</textarea>
                                </div>

                                <!-- Shipping & Returns -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Shipping & Returns</label>
                                    <textarea name="shipping_returns"
                                        class="form-control"
                                        rows="5">{{ old('shipping_returns') }}</textarea>
                                </div>

                                

                            </div>

                            <hr/>
                            <div class="row">
                                <!-- Status -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status" class="form-select">
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!--end::Body-->

                        <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                        <!--end::Footer-->

                    </form>
                  <!--end::Form-->
                </div>
                <!--end::Quick Example-->
              </div>
        </div>
       
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {

        $('#changeproductcategory').on('change', function () {

            var category_id = $(this).val();

            if(category_id != '')
            {
                $.ajax({
                    url: "{{ route('admin.product.get_sub_category') }}",
                    type: "POST",
                    data: {
                        category_id: category_id,
                        _token: "{{ csrf_token() }}"
                    },

                    success: function (response)
                    {
                        $('#changeproductsubcategory').html(response);
                    },

                    error: function ()
                    {
                        alert('Something went wrong');
                    }

                });
            }
            else
            {
                $('#changeproductsubcategory').html(
                    '<option value="">Select Sub Category</option>'
                );
            }

        });

    });
</script>
@endsection
