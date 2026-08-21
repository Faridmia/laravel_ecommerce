
@extends('layouts.app')
@section('style')
	<link rel="stylesheet" href="{{ asset('assets/css/plugins/nouislider/nouislider.css') }}">
@endsection
    @section('content')
        
        <main class="main">
            <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                <div class="container d-flex align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url( $getProduct->getCategory->category_slug)}}">{{ $getProduct->getCategory->name}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url( $getProduct->getCategory->category_slug . '/' . $getProduct->getSubCategory->category_slug )}}">{{ $getProduct->getSubCategory->name}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $getProduct->product_title}}</li>
                    </ol>

                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
                <div class="container">
                    <div class="product-details-top mb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="product-gallery">
                                    @php 
                                        $getProductImage = $getProduct->getImageSingle($getProduct->id);
                                        $image_url = '';
                                    @endphp
                                    <figure class="product-main-image">
                                        
                                        @if($getProductImage && $getProductImage->getImagesLogo())
                                            <img id="product-zoom" src="{{ $getProductImage->getImagesLogo() }}" 
                                                alt="{{ $getProduct->product_title }}" data-zoom-image="{{ $getProductImage->getImagesLogo() }}"
                                                class="product-image">
                                        @else
                                            <img id="product-zoom" src="{{ url('upload/products/woocommerce-placeholder.png') }}" data-zoom-image="{{ url('upload/products/woocommerce-placeholder.png') }}"
                                                alt="No Image" 
                                                class="product-image">
                                        @endif

                                        <a href="#" id="btn-product-gallery" class="btn-product-gallery">
                                            <i class="icon-arrows"></i>
                                        </a>
                                    </figure>

                                    <div id="product-zoom-gallery" class="product-image-gallery">
                                       @foreach($getProduct->getImages as $image)
                                            @php
                                                $imageUrl = $image->getImagesLogo();
                                            @endphp

                                            @if($imageUrl)
                                                <a class="product-gallery-item"
                                                href="#"
                                                data-image="{{ $imageUrl }}"
                                                data-zoom-image="{{ $imageUrl }}">
                                                    <img src="{{ $imageUrl }}" alt="Product Image">
                                                </a>
                                            @endif
                                        @endforeach
                                        

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="product-details">
                                    <h1 class="product-title">{{ $getProduct->product_title}}</h1>

                                     <div class="ratings-container">
                                         <div class="ratings">
                                             <div class="ratings-val" style="width: {{ $avgRating * 20 }}%;"></div>
                                         </div><!-- End .ratings -->
                                         <a class="ratings-text" href="#product-review-link" id="review-link">( {{ $reviewsCount }} {{ $reviewsCount == 1 ? 'Review' : 'Reviews' }} )</a>
                                     </div><!-- End .rating-container -->

                                    <div class="product-price">
                                        @if($getProduct->sale_price && $getProduct->sale_price < $getProduct->price)
                                            <span class="new-price" id="getTotalPrice">${{ number_format($getProduct->sale_price, 2) }}</span>
                                            <span class="old-price" id="getOldPrice">${{ number_format($getProduct->price, 2) }}</span>
                                        @else
                                            <span class="new-price" id="getTotalPrice">${{ number_format($getProduct->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <div class="product-content">
                                        <p>{{ $getProduct->short_description }} </p>
                                    </div>

                                    <form action="{{ url('product/add-to-cart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $getProduct->id }}">
                                        <input type="hidden" name="product_price" value="{{ $getProduct->price }}">
                                        <input type="hidden" name="product_sale_price" value="{{ $getProduct->sale_price }}">
                                        <input type="hidden" name="product_name" value="{{ $getProduct->product_title }}">
                                        <input type="hidden" name="product_slug" value="{{ $getProduct->slug }}">
                                        <input type="hidden" name="product_image" value="{{ $getProductImage ? $getProductImage->getImagesLogo() : '' }}">
                                        <input type="hidden" name="quantity" value="1">
                                    @if( !empty( $getProduct->getColors->count() ) )
                                    <div class="details-filter-row details-row-size">
                                        <label>Color:</label>

                                        <div class="select-custom">
                                            <select name="color_id" id="color_id" class="form-control">


                                            
                                                <option value="">Select a Color</option>
                                               
                                                @foreach( $getProduct->getColors as $color ) 

                                                <option  value="{{ $color->getColor->color_id }}">{{ $color->getColor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif

                                    @if( !empty( $getProduct->getSize->count() ) )
                                    <div class="details-filter-row details-row-size">
                                        <label for="size">Size:</label>
                                        <div class="select-custom">
                                            <select name="size_id" id="size" required class="form-control getPriceBySize">
                                                <option data-price="0" value="">Select a size</option>
                                                @foreach( $getProduct->getSize as $size )
                                                <option data-price="{{ !empty($size->price) ? $size->price : 0 }}" value="{{ $size->id }}">{{ $size->name }} @if(!empty($size->price)) ($ {{ number_format( $size->price, 2 ) }} ) @endif</option>
                                                @endforeach
                                            </select>
                                        </div><!-- End .select-custom -->

                                        
                                    </div>
                                    @endif

                                    <div class="details-filter-row details-row-size">
                                        <label for="qty">Qty:</label>
                                        <div class="product-details-quantity">
                                            <input name="quantity" type="number" id="qty" class="form-control" value="1" min="1" max="100" step="1" data-decimals="0" required>
                                        </div><!-- End .product-details-quantity -->
                                    </div><!-- End .details-filter-row -->

                                    <div class="product-details-action">
                                       
                                        <button style="background:#fff;color:#c96;" type="submit" class="btn-product btn-cart">Add to Cart</button>

                                         @php
                                             $isInWishlist = auth()->check() ? \App\Models\WishlistModel::where('user_id', auth()->id())->where('product_id', $getProduct->id)->exists() : false;
                                             $isInCompare = in_array($getProduct->id, session()->get('compare', []));
                                         @endphp
                                         <div class="details-action-wrapper">
                                             <a href="{{ route('wishlist.add', $getProduct->id) }}" class="btn-product btn-wishlist" title="Wishlist" {!! $isInWishlist ? 'style="color: #c96 !important;"' : '' !!}><span>{{ $isInWishlist ? 'Added to Wishlist' : 'Add to Wishlist' }}</span></a>
                                             <a href="{{ route('compare.add', $getProduct->id) }}" class="btn-product btn-compare" title="Compare" {!! $isInCompare ? 'style="color: #c96 !important;"' : '' !!}><span>{{ $isInCompare ? 'Added to Compare' : 'Add to Compare' }}</span></a>
                                         </div><!-- End .details-action-wrapper -->
                                    </div><!-- End .product-details-action -->
                                    </form>
                                    <div class="product-details-footer">
                                        <div class="product-cat">
                                            <span>Category:</span>

                                            @if($getProduct->getCategory)
                                                <a href="{{ url($getProduct->getCategory->category_slug) }}">
                                                    {{ $getProduct->getCategory->name }}
                                                </a>
                                            @endif

                                            @if($getProduct->getSubCategory)
                                                ,
                                                <a href="{{ url($getProduct->getCategory->category_slug . '/' . $getProduct->getSubCategory->category_slug) }}">
                                                    {{ $getProduct->getSubCategory->name }}
                                                </a>
                                            @endif
                                        </div>

                                        <!-- <div class="social-icons social-icons-sm">
                                            <span class="social-label">Share:</span>
                                            <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                            <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                            <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            <a href="#" class="social-icon" title="Pinterest" target="_blank"><i class="icon-pinterest"></i></a>
                                        </div> -->
                                    </div><!-- End .product-details-footer -->
                                </div><!-- End .product-details -->
                            </div><!-- End .col-md-6 -->
                        </div><!-- End .row -->
                    </div><!-- End .product-details-top -->
                </div><!-- End .container -->

                <div class="product-details-tab product-details-extended">
                    <div class="container">
                        <ul class="nav nav-pills justify-content-center" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="product-desc-link" data-toggle="tab" href="#product-desc-tab" role="tab" aria-controls="product-desc-tab" aria-selected="true">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="product-info-link" data-toggle="tab" href="#product-info-tab" role="tab" aria-controls="product-info-tab" aria-selected="false">Additional information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="product-shipping-link" data-toggle="tab" href="#product-shipping-tab" role="tab" aria-controls="product-shipping-tab" aria-selected="false">Shipping & Returns</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="product-review-link" data-toggle="tab" href="#product-review-tab" role="tab" aria-controls="product-review-tab" aria-selected="false">Reviews ({{ $reviewsCount }})</a>
                            </li>
                        </ul>
                    </div><!-- End .container -->

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="product-desc-tab" role="tabpanel" aria-labelledby="product-desc-link">
                            <div class="product-desc-content">
                                <div class="container">
                                    {!! $getProduct->description !!}
                                </div><!-- End .container -->
                            </div><!-- End .product-desc-content -->
                        </div><!-- .End .tab-pane -->
                        <div class="tab-pane fade" id="product-info-tab" role="tabpanel" aria-labelledby="product-info-link">
                            <div class="product-desc-content">
                                <div class="container">
                                    {!! $getProduct->additional_information !!}
                                </div><!-- End .container -->
                            </div><!-- End .product-desc-content -->
                        </div><!-- .End .tab-pane -->
                        <div class="tab-pane fade" id="product-shipping-tab" role="tabpanel" aria-labelledby="product-shipping-link">
                            <div class="product-desc-content">
                                <div class="container">
                                    {!! $getProduct->shipping_returns !!}
                                </div><!-- End .container -->
                            </div><!-- End .product-desc-content -->
                        </div><!-- .End .tab-pane -->
                        <div class="tab-pane fade" id="product-review-tab" role="tabpanel" aria-labelledby="product-review-link">
                            <div class="reviews">
                                <div class="container">
                                    <h3>Reviews ({{ $reviewsCount }})</h3>
                                    
                                    @foreach($reviews as $review)
                                    <div class="review">
                                        <div class="row no-gutters">
                                            <div class="col-auto" style="min-width: 150px;">
                                                <h4><a href="javascript:void(0);">{{ $review->name }}</a></h4>
                                                <div class="ratings-container">
                                                    <div class="ratings">
                                                        <div class="ratings-val" style="width: {{ $review->rating * 20 }}%;"></div><!-- End .ratings-val -->
                                                    </div><!-- End .ratings -->
                                                </div><!-- End .rating-container -->
                                                <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                            </div><!-- End .col -->
                                            <div class="col">
                                                <div class="review-content" style="padding-left: 2rem;">
                                                    <p style="font-size: 1.4rem; color: #666; line-height: 1.6;">{{ $review->review }}</p>
                                                </div><!-- End .review-content -->
                                            </div><!-- End .col-auto -->
                                        </div><!-- End .row -->
                                    </div><!-- End .review -->
                                    @endforeach

                                    @if($reviews->isEmpty())
                                        <p class="text-muted">No reviews yet for this product. Be the first to leave one!</p>
                                    @endif

                                    <!-- Add Review Form -->
                                     <div class="reply mt-5 pt-4" style="border-top: 1px solid #ebebeb;">
                                         <h3 class="mb-2">Write a Review</h3>
                                         @if(session('success'))
                                             <div class="alert alert-success mb-3">
                                                 {{ session('success') }}
                                             </div>
                                         @endif
                                         @if($errors->any())
                                             <div class="alert alert-danger mb-3">
                                                 <ul class="mb-0">
                                                     @foreach($errors->all() as $error)
                                                         <li>{{ $error }}</li>
                                                     @endforeach
                                                 </ul>
                                             </div>
                                         @endif

                                         @if(!auth()->check())
                                             <div class="alert alert-warning mb-3" style="font-size: 1.4rem; padding: 1.5rem 2rem;">
                                                 <i class="icon-warning" style="margin-right: 1rem; color: #e0b034;"></i> Only logged-in customers who have purchased this product may leave a review.
                                             </div>
                                         @elseif(!$userHasPurchased)
                                             <div class="alert alert-warning mb-3" style="font-size: 1.4rem; padding: 1.5rem 2rem;">
                                                 <i class="icon-warning" style="margin-right: 1rem; color: #e0b034;"></i> Only logged-in customers who have purchased this product may leave a review.
                                             </div>
                                         @else
                                             <form action="{{ route('product.review.submit') }}" method="POST">
                                                 {{ csrf_field() }}
                                                 <input type="hidden" name="product_id" value="{{ $getProduct->id }}">
                                                 
                                                 <div class="row mb-3">
                                                     <div class="col-md-6 col-sm-12">
                                                         <label for="rating" style="font-weight: 500; color: #333;">Your Rating <span class="text-danger">*</span></label>
                                                         <select name="rating" id="rating" class="form-control" required style="max-width: 250px;">
                                                             <option value="">Select a rating...</option>
                                                             <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 Stars - Excellent</option>
                                                             <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Stars - Good</option>
                                                             <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Stars - Average</option>
                                                             <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Stars - Not Bad</option>
                                                             <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Star - Poor</option>
                                                         </select>
                                                     </div>
                                                 </div>

                                                 <div class="row mb-3">
                                                     <div class="col-12">
                                                         <label for="review" style="font-weight: 500; color: #333;">Your Review <span class="text-danger">*</span></label>
                                                         <textarea class="form-control" id="review" name="review" rows="4" placeholder="Write your review here... (minimum 5 characters)" required>{{ old('review') }}</textarea>
                                                     </div>
                                                 </div>

                                                 <button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm">
                                                     <span>SUBMIT REVIEW</span>
                                                     <i class="icon-long-arrow-right"></i>
                                                 </button>
                                             </form>
                                         @endif
                                     </div><!-- End .reply -->
                                </div><!-- End .container -->
                            </div><!-- End .reviews -->
                        </div><!-- .End .tab-pane -->
                    </div><!-- End .tab-content -->
                </div><!-- End .product-details-tab -->

                <div class="container">
                    <h2 class="title text-center mb-4">You May Also Like</h2><!-- End .title text-center -->
                    <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                        data-owl-options='{
                            "nav": false, 
                            "dots": true,
                            "margin": 20,
                            "loop": false,
                            "responsive": {
                                "0": {
                                    "items":1
                                },
                                "480": {
                                    "items":2
                                },
                                "768": {
                                    "items":3
                                },
                                "992": {
                                    "items":4
                                },
                                "1200": {
                                    "items":4,
                                    "nav": true,
                                    "dots": false
                                }
                            }
                        }'>
                       
                       @foreach($getRelatedProduct as $product)

                        @php
                            $productImage = $product->getImages->first();
                        @endphp

                        <div class="product product-7">
                            <figure class="product-media">

                                <span class="product-label label-top">Top</span>

                                <a href="{{ url($product->slug) }}">

                                    @if($productImage && $productImage->getImagesLogo())
                                        <img src="{{ $productImage->getImagesLogo() }}"
                                            alt="{{ $product->product_title }}"
                                            class="product-image">
                                    @else
                                        <img src="{{ url('upload/products/woocommerce-placeholder.png') }}"
                                            alt="No Image"
                                            class="product-image">
                                    @endif

                                </a>

                                 @php
                                     $isRelatedInWishlist = auth()->check() ? \App\Models\WishlistModel::where('user_id', auth()->id())->where('product_id', $product->id)->exists() : false;
                                     $isRelatedInCompare = in_array($product->id, session()->get('compare', []));
                                 @endphp
                                 <div class="product-action-vertical">
                                      <a href="{{ route('wishlist.add', $product->id) }}" class="btn-product-icon btn-wishlist btn-expandable" {!! $isRelatedInWishlist ? 'style="background-color: #c96 !important; color: #fff !important;"' : '' !!}>
                                          <span>Add to wishlist</span>
                                      </a>
                                      <a href="{{ route('compare.add', $product->id) }}" class="btn-product-icon btn-compare" title="Compare product" style="display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; margin-top: 5px; border: 1px solid #ebebeb; {!! $isRelatedInCompare ? 'background-color: #c96 !important; color: #fff !important;' : 'background-color: #fff !important; color: #666 !important;' !!}"><span>Compare</span></a>
                                  </div>

                                <!-- <div class="product-action">
                                    <a href="#" class="btn-product btn-cart">
                                        <span>Add to cart</span>
                                    </a>
                                </div> -->

                            </figure>

                            <div class="product-body">

                                <div class="product-cat">
                                    <a href="{{ url($product->category_slug. '/' . $product->sub_category_slug ) }}">
                                        {{ $product->sub_category_name }}
                                    </a>
                                    
                                </div>

                                <h3 class="product-title">
                                    <a href="{{ url($product->slug) }}">
                                        {{ $product->product_title }}
                                    </a>
                                </h3>

                                <div class="product-price">
                                    @if($product->old_price && $product->old_price > $product->price)
                                        <span class="new-price">${{ number_format($product->price, 2) }}</span>
                                        <span class="old-price">${{ number_format($product->old_price, 2) }}</span>
                                    @else
                                        ${{ number_format($product->price, 2) }}
                                    @endif
                                </div>

                                @php
                                    $relatedAvgRating = $product->getAvgRating();
                                    $relatedReviewsCount = $product->getReviewsCount();
                                @endphp
                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-val" style="width: {{ $relatedAvgRating * 20 }}%;"></div>
                                    </div>
                                    <span class="ratings-text">({{ $relatedReviewsCount }} {{ $relatedReviewsCount == 1 ? 'Review' : 'Reviews' }})</span>
                                </div>

                            </div>
                        </div>

                    @endforeach
                       
                    </div>
                </div>
            </div>
        </main>
        
@endsection

@section('script')
	<script src="{{ asset('assets/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.elevateZoom.min.js') }}"></script>

    <script>
    $('.getPriceBySize').on('change', function () {

        let regularPrice = parseFloat('{{ $getProduct->price }}') || 0;
        let salePrice    = parseFloat('{{ $getProduct->sale_price ?? 0 }}') || 0;

        let sizePrice = parseFloat($(this).find(':selected').data('price')) || 0;

        console.log({
            regularPrice,
            salePrice,
            sizePrice,
            oldPriceElement: $('#getOldPrice').length
        });

        if (salePrice > 0 && salePrice < regularPrice) {

            $('#getTotalPrice').text('$' + (salePrice + sizePrice).toFixed(2));
            $('#getOldPrice').text('$' + (regularPrice + sizePrice).toFixed(2));

        } else {

            $('#getTotalPrice').text('$' + (regularPrice + sizePrice).toFixed(2));

        }

    });
</script>
	
@endsection