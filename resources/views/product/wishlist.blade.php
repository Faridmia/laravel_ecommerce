@extends('layouts.app')

@section('style')
@endsection

@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}')">
        <div class="container">
            <h1 class="page-title">My Wishlist<span>Shop</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
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

            @if($wishlist->count() > 0)
                <table class="table table-wishlist table-mobile">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock Status</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($wishlist as $item)
                        @php
                            $product = $item->product;
                            if (!$product) continue;

                            $image = App\Models\ProductImageModel::where('product_id', $product->id)->orderBy('order_by', 'asc')->first();
                            $imageSrc = $image ? $image->getImagesLogo() : null;
                            $slug = $product->slug;
                            $title = $product->product_title;

                            // Check if has variations
                            $hasSizes = App\Models\ProductSizeModel::where('product_id', $product->id)->exists();
                            $hasColors = App\Models\ProductColorModel::where('product_id', $product->id)->exists();
                            $hasVariations = $hasSizes || $hasColors;
                        @endphp
                        <tr>
                            <td class="product-col">
                                <div class="product">
                                    <figure class="product-media">
                                        <a href="{{ url($slug) }}">
                                            <img src="{{ $imageSrc ? $imageSrc : asset('assets/images/products/table/product-1.jpg') }}" alt="{{ $title }}">
                                        </a>
                                    </figure>

                                    <h3 class="product-title">
                                        <a href="{{ url($slug) }}">{{ $title }}</a>
                                    </h3><!-- End .product-title -->
                                </div><!-- End .product -->
                            </td>
                            <td class="price-col">
                                @if(!empty($product->sale_price) && $product->sale_price < $product->price)
                                    <span class="new-price">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="old-price" style="text-decoration: line-through; color: #999; margin-left: 5px;">${{ number_format($product->price, 2) }}</span>
                                @else
                                    ${{ number_format($product->price, 2) }}
                                @endif
                            </td>
                            <td class="stock-col">
                                @if($product->status == 0)
                                    <span class="in-stock">In Stock</span>
                                @else
                                    <span class="out-of-stock" style="color: red;">Out of Stock</span>
                                @endif
                            </td>
                            <td class="action-col">
                                @if($product->status == 0)
                                    @if($hasVariations)
                                        <a href="{{ url($slug) }}" class="btn btn-block btn-outline-primary-2">
                                            <i class="icon-list-alt"></i>Select Options
                                        </a>
                                    @else
                                        <form action="{{ url('product/add-to-cart') }}" method="POST" style="margin: 0;">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-block btn-outline-primary-2">
                                                <i class="icon-shopping-cart"></i>Add to Cart
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button class="btn btn-block btn-outline-primary-2 disabled" disabled>Out of Stock</button>
                                @endif
                            </td>
                            <td class="remove-col">
                                <a href="{{ route('wishlist.remove', $item->id) }}" class="btn-remove" title="Remove Product">
                                    <i class="icon-close"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table><!-- End .table table-wishlist -->
            @else
                <div class="text-center py-5">
                    <i class="icon-heart-o" style="font-size: 6rem; color: #ccc;"></i>
                    <h3 class="mt-3">Your wishlist is empty.</h3>
                    <p class="text-muted">Save your favorite products here to buy them later.</p>
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
