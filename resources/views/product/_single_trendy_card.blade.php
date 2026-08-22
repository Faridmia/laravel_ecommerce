@php
    $product_images = $product->getImages;
    $first_image = !empty($product_images[0]) ? $product_images[0]->getImagesLogo() : url('upload/products/woocommerce-placeholder.png');
    $second_image = !empty($product_images[1]) ? $product_images[1]->getImagesLogo() : $first_image;
    
    $isInWishlist = auth()->check() ? \App\Models\WishlistModel::where('user_id', auth()->id())->where('product_id', $product->id)->exists() : false;
    
    $discount = 0;
    if (!empty($product->sale_price) && $product->sale_price < $product->price) {
        $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
    }
@endphp

<div class="product product-11 text-center">
    <figure class="product-media">
        @if($discount > 0)
            <span class="product-label label-sale">{{ $discount }}% OFF</span>
        @elseif($product->created_at >= now()->subDays(7))
            <span class="product-label label-new">NEW</span>
        @endif
        <a href="{{ url($product->slug) }}">
            <img src="{{ $first_image }}" alt="{{ $product->product_title }}" class="product-image">
            @if($second_image)
                <img src="{{ $second_image }}" alt="{{ $product->product_title }}" class="product-image-hover">
            @endif
        </a>

        <div class="product-action-vertical">
            <a href="{{ route('wishlist.add', $product->id) }}" class="btn-product-icon btn-wishlist" {!! $isInWishlist ? 'style="background-color: #c96 !important; color: #fff !important;"' : '' !!}><span>add to wishlist</span></a>
        </div><!-- End .product-action-vertical -->
    </figure><!-- End .product-media -->

    <div class="product-body">
        <h3 class="product-title"><a href="{{ url($product->slug) }}">{{ $product->product_title }}</a></h3><!-- End .product-title -->
        <div class="product-price">
            @if($discount > 0)
                <span class="new-price">${{ number_format($product->sale_price, 2) }}</span>
                <span class="old-price">Was ${{ number_format($product->price, 2) }}</span>
            @else
                ${{ number_format($product->price, 2) }}
            @endif
        </div><!-- End .product-price -->
    </div><!-- End .product-body -->
    <div class="product-action">
        <a href="{{ url($product->slug) }}" class="btn-product btn-cart"><span>add to cart</span></a>
    </div><!-- End .product-action -->
</div><!-- End .product -->
