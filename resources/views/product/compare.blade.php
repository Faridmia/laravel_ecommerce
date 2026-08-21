@extends('layouts.app')

@section('style')
<style>
    .table-compare {
        table-layout: fixed;
        width: 100%;
        background-color: #fff;
    }
    .table-compare th, .table-compare td {
        padding: 1.2rem 1.5rem !important;
        vertical-align: middle !important;
        border-bottom: 0.1rem solid #ebebeb;
        text-align: center;
    }
    .table-compare th {
        font-weight: 500;
        color: #333;
        text-transform: uppercase;
        background-color: #fafafa;
        width: 200px;
        text-align: left;
        vertical-align: middle !important;
    }
    .table-compare .product-col-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: #333;
    }
    .table-compare .product-image-box {
        max-width: 150px;
        margin: 0 auto;
    }
    .table-compare .remove-col-btn {
        color: #cccccc;
        font-size: 1.6rem;
        transition: color 0.3s;
    }
    .table-compare .remove-col-btn:hover {
        color: #ff3333;
    }
    .table-compare .stock-col-status {
        font-weight: 600;
    }
    .table-compare .stock-col-status.in-stock {
        color: #a6c76c;
    }
    .table-compare .stock-col-status.out-of-stock {
        color: #ef837b;
    }
</style>
@endsection

@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}')">
        <div class="container">
            <h1 class="page-title">Compare Products<span>Shop</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Compare</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content text-start">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0" style="padding-left: 15px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-compare">
                        <tbody>
                            <!-- Row 1: Remove Buttons -->
                            <tr>
                                <th></th>
                                @foreach($products as $product)
                                <td>
                                    <a href="{{ route('compare.remove', $product->id) }}" class="remove-col-btn" title="Remove product">
                                        <i class="icon-close"></i> Remove
                                    </a>
                                </td>
                                @endforeach
                            </tr>

                            <!-- Row 2: Product Images & Info -->
                            <tr>
                                <th>Product</th>
                                @foreach($products as $product)
                                @php
                                    $image = App\Models\ProductImageModel::where('product_id', $product->id)->orderBy('order_by', 'asc')->first();
                                    $imageSrc = $image ? $image->getImagesLogo() : null;
                                    $slug = $product->slug;
                                    $title = $product->product_title;

                                    $hasSizes = App\Models\ProductSizeModel::where('product_id', $product->id)->exists();
                                    $hasColors = App\Models\ProductColorModel::where('product_id', $product->id)->exists();
                                    $hasVariations = $hasSizes || $hasColors;
                                @endphp
                                <td>
                                    <div class="product-image-box">
                                        <a href="{{ url($slug) }}">
                                            <img src="{{ $imageSrc ? $imageSrc : asset('assets/images/products/table/product-1.jpg') }}" alt="{{ $title }}" style="width: 100%; border-radius: 5px;">
                                        </a>
                                    </div>
                                    <h3 class="product-col-title">
                                        <a href="{{ url($slug) }}">{{ $title }}</a>
                                    </h3>
                                    
                                    @if($product->status == 0)
                                        @if($hasVariations)
                                            <a href="{{ url($slug) }}" class="btn btn-outline-primary-2 btn-sm">Select Options</a>
                                        @else
                                            <form action="{{ url('product/add-to-cart') }}" method="POST" style="margin: 0;">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-outline-primary-2 btn-sm">Add to Cart</button>
                                            </form>
                                        @endif
                                    @else
                                        <button class="btn btn-sm btn-outline-primary-2 disabled" disabled>Out of Stock</button>
                                    @endif
                                </td>
                                @endforeach
                            </tr>

                            <!-- Row 3: Prices -->
                            <tr>
                                <th>Price</th>
                                @foreach($products as $product)
                                <td>
                                    @if(!empty($product->sale_price) && $product->sale_price < $product->price)
                                        <span class="text-primary fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                        <span style="text-decoration: line-through; color: #999; margin-left: 5px;">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>

                            <!-- Row 4: Availability -->
                            <tr>
                                <th>Availability</th>
                                @foreach($products as $product)
                                <td>
                                    @if($product->status == 0)
                                        <span class="stock-col-status in-stock">In Stock</span>
                                    @else
                                        <span class="stock-col-status out-of-stock">Out of Stock</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>

                            <!-- Row 5: Category -->
                            <tr>
                                <th>Category</th>
                                @foreach($products as $product)
                                <td>
                                    {{ $product->getCategory ? $product->getCategory->name : 'N/A' }}
                                </td>
                                @endforeach
                            </tr>

                            <!-- Row 6: Description -->
                            <tr>
                                <th>Description</th>
                                @foreach($products as $product)
                                <td>
                                    <div style="font-size: 1.2rem; max-height: 120px; overflow-y: auto; text-align: left; line-height: 1.6;">
                                        {!! !empty($product->short_description) ? $product->short_description : 'No description available.' !!}
                                    </div>
                                </td>
                                @endforeach
                            </tr>

                            <!-- Row 7: Colors -->
                            <tr>
                                <th>Colors</th>
                                @foreach($products as $product)
                                @php
                                    $colors = App\Models\ProductColorModel::where('product_id', $product->id)
                                        ->join('colors', 'colors.color_id', '=', 'product_colors.color_id')
                                        ->get(['colors.name', 'colors.code']);
                                @endphp
                                <td>
                                    @if($colors->count() > 0)
                                        <div class="d-flex justify-content-center flex-wrap gap-1">
                                            @foreach($colors as $color)
                                                <span class="badge text-bg-light border" style="padding: 5px 8px; font-size:1.1rem; display:inline-flex; align-items:center;">
                                                    <span style="background-color: {{ $color->code }}; width:10px; height:10px; border-radius:50%; display:inline-block; margin-right:5px; border:1px solid #ddd;"></span>
                                                    {{ $color->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>

                            <!-- Row 8: Sizes -->
                            <tr>
                                <th>Sizes</th>
                                @foreach($products as $product)
                                @php
                                    $sizes = App\Models\ProductSizeModel::where('product_id', $product->id)
                                        ->get(['name']);
                                @endphp
                                <td>
                                    @if($sizes->count() > 0)
                                        <div class="d-flex justify-content-center flex-wrap gap-1">
                                            @foreach($sizes as $size)
                                                <span class="badge text-bg-light border" style="padding: 5px 8px; font-size:1.1rem;">
                                                    {{ $size->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="icon-random" style="font-size: 6rem; color: #ccc;"></i>
                    <h3 class="mt-3">No products added for comparison.</h3>
                    <p class="text-muted">Add products to compare their prices, descriptions, and attributes side-by-side.</p>
                    <a href="{{ route('shop') }}" class="btn btn-outline-primary-2 mt-2">
                        <span>Go Shopping</span>
                        <i class="icon-long-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
@endsection
