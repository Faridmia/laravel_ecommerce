
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
                                            <div class="ratings-val" style="width: 80%;"></div>
                                        </div><!-- End .ratings -->
                                        <a class="ratings-text" href="#product-review-link" id="review-link">( 2 Reviews )</a>
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
                                       
                                        <button style="background:#fff;color:#c96;" type="submit" class="btn-product btn-cart">add to cart</button>

                                        <div class="details-action-wrapper">
                                            <a href="#" class="btn-product btn-wishlist" title="Wishlist"><span>Add to Wishlist</span></a>
                                           
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
                                <a class="nav-link" id="product-review-link" data-toggle="tab" href="#product-review-tab" role="tab" aria-controls="product-review-tab" aria-selected="false">Reviews (2)</a>
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
                                    <h3>Reviews (2)</h3>
                                    <div class="review">
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <h4><a href="#">Samanta J.</a></h4>
                                                <div class="ratings-container">
                                                    <div class="ratings">
                                                        <div class="ratings-val" style="width: 80%;"></div><!-- End .ratings-val -->
                                                    </div><!-- End .ratings -->
                                                </div><!-- End .rating-container -->
                                                <span class="review-date">6 days ago</span>
                                            </div><!-- End .col -->
                                            <div class="col">
                                                <h4>Good, perfect size</h4>

                                                <div class="review-content">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus cum dolores assumenda asperiores facilis porro reprehenderit animi culpa atque blanditiis commodi perspiciatis doloremque, possimus, explicabo, autem fugit beatae quae voluptas!</p>
                                                </div><!-- End .review-content -->

                                                <div class="review-action">
                                                    <a href="#"><i class="icon-thumbs-up"></i>Helpful (2)</a>
                                                    <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                                </div><!-- End .review-action -->
                                            </div><!-- End .col-auto -->
                                        </div><!-- End .row -->
                                    </div><!-- End .review -->

                                    <div class="review">
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <h4><a href="#">John Doe</a></h4>
                                                <div class="ratings-container">
                                                    <div class="ratings">
                                                        <div class="ratings-val" style="width: 100%;"></div><!-- End .ratings-val -->
                                                    </div><!-- End .ratings -->
                                                </div><!-- End .rating-container -->
                                                <span class="review-date">5 days ago</span>
                                            </div><!-- End .col -->
                                            <div class="col">
                                                <h4>Very good</h4>

                                                <div class="review-content">
                                                    <p>Sed, molestias, tempore? Ex dolor esse iure hic veniam laborum blanditiis laudantium iste amet. Cum non voluptate eos enim, ab cumque nam, modi, quas iure illum repellendus, blanditiis perspiciatis beatae!</p>
                                                </div><!-- End .review-content -->

                                                <div class="review-action">
                                                    <a href="#"><i class="icon-thumbs-up"></i>Helpful (0)</a>
                                                    <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                                </div><!-- End .review-action -->
                                            </div><!-- End .col-auto -->
                                        </div><!-- End .row -->
                                    </div><!-- End .review -->
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

                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-wishlist btn-expandable">
                                        <span>Add to wishlist</span>
                                    </a>
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

                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-val" style="width:80%;"></div>
                                    </div>
                                    <span class="ratings-text">(0 Reviews)</span>
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