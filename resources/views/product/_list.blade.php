<div class="products mb-3">
    <div class="row justify-content-center">
        @foreach( $getProduct as $product )

        @php 
            $getProductImage = $product->getImageSingle($product->id);
            $image_url = '';
        @endphp

        <div class="col-12 col-md-4 col-lg-4">
            <div class="product product-7 text-center">
                <figure class="product-media">
                    
                    <a href="{{ url($product->slug) }}">
                        @if($getProductImage && $getProductImage->getImagesLogo())
                            <img src="{{ $getProductImage->getImagesLogo() }}" 
                                alt="{{ $product->product_title }}" 
                                class="product-image">
                        @else
                            <img src="{{ url('upload/products/woocommerce-placeholder.png') }}" 
                                alt="No Image" 
                                class="product-image">
                        @endif
                    </a>

                    @php
                        $isInWishlist = auth()->check() ? \App\Models\WishlistModel::where('user_id', auth()->id())->where('product_id', $product->id)->exists() : false;
                        $isInCompare = in_array($product->id, session()->get('compare', []));
                    @endphp
                    <div class="product-action-vertical">
                        <a href="{{ route('wishlist.add', $product->id) }}" class="btn-product-icon btn-wishlist btn-expandable" {!! $isInWishlist ? 'style="background-color: #c96 !important; color: #fff !important;"' : '' !!}><span>add to wishlist</span></a>
                        <a href="{{ route('compare.add', $product->id) }}" class="btn-product-icon btn-compare" title="Compare product" style="display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; margin-top: 5px; border: 1px solid #ebebeb; {!! $isInCompare ? 'background-color: #c96 !important; color: #fff !important;' : 'background-color: #fff !important; color: #666 !important;' !!}"><span>Compare</span></a>
                    </div>
                </figure>

                <div class="product-body">
                    <div class="product-cat">
                        <a href="{{url($product->category_slug .'/'. $product->sub_category_slug)}}">{{$product->sub_category_name}}</a>
                    </div>
                    <h3 class="product-title"><a href="{{url($product->slug)}}">{{$product->product_title}}</a></h3>
                    <div class="product-price">
                        ${{number_format($product->price, 2)}}
                    </div>
                    @php
                        $listAvgRating = $product->getAvgRating();
                        $listReviewsCount = $product->getReviewsCount();
                    @endphp
                    <div class="ratings-container">
                        <div class="ratings">
                            <div class="ratings-val" style="width: {{ $listAvgRating * 20 }}%;"></div>
                        </div>
                        <span class="ratings-text">( {{ $listReviewsCount }} {{ $listReviewsCount == 1 ? 'Review' : 'Reviews' }} )</span>
                    </div>

                    
                </div>
            </div>
        </div>
        @endforeach
        
    </div><!-- End .row -->
</div><!-- End .products -->

<!-- <div class="d-flex justify-content-center">
    {{ $getProduct->links() }}
</div> -->