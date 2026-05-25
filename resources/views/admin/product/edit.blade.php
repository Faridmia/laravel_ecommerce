@extends('admin.layouts.app')
@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Product</h1>
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
                    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <!--begin::Body-->
                        <div class="card-body">

                            <div class="row">

                                <!-- Product Title -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Product Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="product_title"
                                        value="{{ old('product_title', $product->product_title) }}"
                                        class="form-control">
                                </div>

                                <!-- Slug -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug"
                                        value="{{ old('slug', $product->slug) }}"
                                        class="form-control">
                                </div>

                                <!-- SKU -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="sku"
                                        value="{{ old('sku', $product->sku) }}"
                                        class="form-control">
                                </div>

                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" id="changeproductcategory">
                                        <option value="">Select Category</option>

                                        @foreach($categories as $category)
                                             <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                            <option value="{{ old('sub_category_id', $product->sub_category_id) }}" selected>
                                                {{ old('sub_category_id', $product->sub_category_id) ? $subcategories->where('id', old('sub_category_id', $product->sub_category_id))->first()->name : '' }}
                                            </option>
                                       
                                    </select>
                                </div>

                                <!-- Brand -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-select">
                                        @foreach($brands as $brand)

                                            <option value="{{ $brand->brand_id }}"
                                                {{ old('brand_id', $product->brand_id) == $brand->brand_id ? 'selected' : '' }}>

                                                {{ $brand->name }}

                                            </option>

                                        @endforeach
                                       
                                    </select>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Price <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"  name="price"
                                        value="{{ old('price', $product->price) }}"
                                        class="form-control">
                                </div>

                                <!-- Sale Price -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price</label>
                                    <input type="number"  name="sale_price"
                                        value="{{ old('sale_price', $product->sale_price) }}"
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
                                      @php
                                          $selectedColors = $product->getColors->pluck('color_id')->toArray();
                                      @endphp

                                      @foreach($colors as $color)
                                      <div class="form-check">

                                          <input class="form-check-input"
                                              type="checkbox"
                                              name="color_id[]"
                                              value="{{ $color->color_id }}"
                                              id="color{{ $color->color_id }}"

                                              {{ in_array($color->color_id, $selectedColors) ? 'checked' : '' }}
                                          >

                                          <label class="form-check-label"
                                              for="color{{ $color->color_id }}">
                                              {{ $color->name }}
                                          </label>

                                      </div>
                                      @endforeach
                                    </div>

                                </div>
                                <!-- Image -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Product Images</label>

                                    <input 
                                        type="file" 
                                        name="image[]" 
                                        class="form-control"
                                        id="imageInput"
                                        multiple 
                                        accept="image/*"
                                    >

                                    <!-- Preview Area -->
                                    <div class="row mt-3" id="previewContainer"></div>
                                </div>

                                <div class="row mt-3" id="sortable">

                                  @if($product->getImages && $product->getImages->count())

                                      @foreach($product->getImages as $image)
                                        @if(!empty($image->getImagesLogo()))

                                          <div class="col-md-2 mb-3 sortable_image" id="{{ $image->id }}">

                                              <img src="{{ $image->getImagesLogo() }}"
                                                  class="img-fluid border rounded"
                                                  style="height:120px; object-fit:cover; width:100%;">

                                                <a href="{{ route('admin.product.delete_image', $image->id) }}"
                                                    class="btn btn-sm btn-danger mt-2 d-block text-center"
                                                    onclick="return confirm('Are you sure you want to delete this image?')" style="margin-top: 10px;">
                                                    Delete
                                                </a>

                                          </div>
                                          @endif

                                      @endforeach

                                  @endif

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
                                                @forelse($product->getSize as $size)

                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                            name="size[{{ $size->id }}][name]"
                                                            value="{{ $size->name }}"
                                                            class="form-control"
                                                            placeholder="Size Name">
                                                    </td>

                                                    <td>
                                                        <input type="text"
                                                            name="size[{{ $size->id }}][price]"
                                                            value="{{ $size->price }}"
                                                            class="form-control"
                                                            placeholder="Size Price">
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

                                                @empty

                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                            name="size[100][name]"
                                                            class="form-control"
                                                            placeholder="Size Name">
                                                    </td>

                                                    <td>
                                                        <input type="text"
                                                            name="size[100][price]"
                                                            class="form-control"
                                                            placeholder="Size Price">
                                                    </td>

                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-success add-size-row addsizerow">
                                                            Add
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-danger remove-size-row removesizerow">
                                                            Remove
                                                        </button>
                                                    </td>
                                                </tr>

                                                @endforelse
                                            </tbody>

                                            
                                        </table>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description"
                                        class="form-control ecommerc-editor"
                                        rows="5">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <!-- Short Description -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="short_description"
                                        class="form-control ecommerc-editor"
                                        rows="4">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>

                                <!-- Additional Information -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Additional Information</label>
                                    <textarea name="additional_information"
                                        class="form-control ecommerc-editor"
                                        rows="4">{{ old('additional_information', $product->additional_information) }}</textarea>
                                </div>

                                <!-- Shipping & Returns -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Shipping & Returns</label>
                                    <textarea name="shipping_returns"
                                        class="form-control ecommerc-editor"
                                        rows="5">{{ old('shipping_returns', $product->shipping_returns) }}</textarea>
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

                                        <option value="0"
                                            {{ old('status', $product->status) == 0 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="1"
                                            {{ old('status', $product->status) == 1 ? 'selected' : '' }}>
                                            Inactive
                                        </option>

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
<script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.9.3/tinymce.min.js"></script>

<script>

   $(document).ready(function() {

    $("#sortable").sortable({

        update: function(event, ui) {

            var photo_id = [];

            $('.sortable_image').each(function(){

                let id = $(this).attr('id');

                photo_id.push(id);

            });

            $.ajax({

                url: "{{ route('admin.product.update_image_order') }}",
                method: "POST",
                data: {

                    photo_id: photo_id,

                    _token: "{{ csrf_token() }}"

                },
                dataType: "json",
                success: function(response) {

                    console.log(response);

                },

                error: function() {

                    alert('Something went wrong');

                }   
            })

        }

    });

});


    document.getElementById('imageInput').addEventListener('change', function(event) {

        const previewContainer = document.getElementById('previewContainer');

        previewContainer.innerHTML = '';

        const files = event.target.files;

        if(files.length > 0){

            Array.from(files).forEach(file => {

                // Only image check
                if(!file.type.startsWith('image/')){
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e){

                    const col = document.createElement('div');
                    col.classList.add('col-md-2', 'mb-3');

                    col.innerHTML = `
                        <div class="border rounded p-2 text-center">
                            <img 
                                src="${e.target.result}" 
                                class="img-fluid rounded"
                                style="height:120px; object-fit:cover; width:100%;"
                            >
                        </div>
                    `;

                    previewContainer.appendChild(col);
                }

                reader.readAsDataURL(file);

            });

        }

    });
</script>
    
<script>
    tinymce.init({
        selector: '.ecommerc-editor',
        height: 250,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic backcolor | ' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | removeformat | help'
    });
</script>
<script>
    $(document).ready(function () {

        let i = 101;

        $('body').delegate('.add-size-row', 'click', function () {
            var newRow = '<tr id="deletesize' + i + '">' +
                '<td><input type="text" name="size[' + i + '][name]" class="form-control" placeholder="Size Name"></td>' +
                '<td><input type="text" name="size[' + i + '][price]" class="form-control" placeholder="Size Price"></td>' +
                '<td>' +
                '<button type="button" class="btn btn-sm btn-success add-size-row addsizerow">Add</button> ' +
                '<button type="button" id="deletesize' + i + '" class="btn btn-sm btn-danger remove-size-row removesizerow">Remove</button>' +
                '</td>' +
                '</tr>';
                i++;
            $('.size-table tbody').append(newRow);
        });

        // Remove Row
        $('body').on('click', '.remove-size-row', function () {

            $(this).closest('tr').remove();

        });

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