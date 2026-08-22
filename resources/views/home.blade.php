
@extends('layouts.app')
@section('style')
<style>
    .categories .banner {
        margin-bottom: 0 !important;
    }
</style>
@endsection
@section('content')
        
        <main class="main">
            <div class="intro-section bg-lighter pt-5 pb-6">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="intro-slider-container slider-container-ratio slider-container-1 mb-2 mb-lg-0">
                                <div class="intro-slider intro-slider-1 owl-carousel owl-simple owl-light owl-nav-inside" data-toggle="owl" data-owl-options='{
                                        "nav": false, 
                                        "responsive": {
                                            "768": {
                                                "nav": true
                                            }
                                        }
                                    }'>
                                    @foreach($sliders as $slider)
                                    <div class="intro-slide">
                                        <figure class="slide-image">
                                            <picture>
                                                <img src="{{ $slider->getImageUrl() }}" alt="{{ $slider->title ?? 'Slider Image' }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            </picture>
                                        </figure><!-- End .slide-image -->

                                        <div class="intro-content">
                                            @if(!empty($slider->subtitle))
                                                <h3 class="intro-subtitle">{{ $slider->subtitle }}</h3><!-- End .h3 intro-subtitle -->
                                            @endif
                                            @if(!empty($slider->title))
                                                <h1 class="intro-title">{!! $slider->title !!}</h1><!-- End .intro-title -->
                                            @endif

                                            @if(!empty($slider->button_text))
                                                <a href="{{ !empty($slider->button_link) ? url($slider->button_link) : '#' }}" class="btn btn-outline-white">
                                                    <span>{{ $slider->button_text }}</span>
                                                    <i class="icon-long-arrow-right"></i>
                                                </a>
                                            @endif
                                        </div><!-- End .intro-content -->
                                    </div><!-- End .intro-slide -->
                                    @endforeach
                                </div><!-- End .intro-slider owl-carousel owl-simple -->
                                
                                <span class="slider-loader"></span><!-- End .slider-loader -->
                            </div><!-- End .intro-slider-container -->
                        </div><!-- End .col-lg-8 -->
                        <div class="col-lg-4">
                            <div class="intro-banners">
                                <div class="row row-sm">
                                    <div class="col-md-6 col-lg-12">
                                        <div class="banner banner-display">
                                            <a href="#">
                                                <img src="assets/images/banners/home/intro/banner-1.jpg" alt="Banner">
                                            </a>

                                            <div class="banner-content">
                                                <h4 class="banner-subtitle text-darkwhite"><a href="#">Clearence</a></h4><!-- End .banner-subtitle -->
                                                <h3 class="banner-title text-white"><a href="#">Chairs & Chaises <br>Up to 40% off</a></h3><!-- End .banner-title -->
                                                <a href="#" class="btn btn-outline-white banner-link">Shop Now<i class="icon-long-arrow-right"></i></a>
                                            </div><!-- End .banner-content -->
                                        </div><!-- End .banner -->
                                    </div><!-- End .col-md-6 col-lg-12 -->

                                    <div class="col-md-6 col-lg-12">
                                        <div class="banner banner-display mb-0">
                                            <a href="#">
                                                <img src="assets/images/banners/home/intro/banner-2.jpg" alt="Banner">
                                            </a>

                                            <div class="banner-content">
                                                <h4 class="banner-subtitle text-darkwhite"><a href="#">New in</a></h4><!-- End .banner-subtitle -->
                                                <h3 class="banner-title text-white"><a href="#">Best Lighting <br>Collection</a></h3><!-- End .banner-title -->
                                                <a href="#" class="btn btn-outline-white banner-link">Discover Now<i class="icon-long-arrow-right"></i></a>
                                            </div><!-- End .banner-content -->
                                        </div><!-- End .banner -->
                                    </div><!-- End .col-md-6 col-lg-12 -->
                                </div><!-- End .row row-sm -->
                            </div><!-- End .intro-banners -->
                        </div><!-- End .col-lg-4 -->
                    </div><!-- End .row -->

                    <div class="mb-6"></div><!-- End .mb-6 -->

                    <div class="owl-carousel owl-simple" data-toggle="owl" 
                        data-owl-options='{
                            "nav": false, 
                            "dots": false,
                            "margin": 30,
                            "loop": false,
                            "responsive": {
                                "0": {
                                    "items":2
                                },
                                "420": {
                                    "items":3
                                },
                                "600": {
                                    "items":4
                                },
                                "900": {
                                    "items":5
                                },
                                "1024": {
                                    "items":6
                                }
                            }
                        }'>
                        @foreach($partners as $partner)
                        <a href="{{ !empty($partner->link) ? url($partner->link) : '#' }}" class="brand" target="_blank">
                            <img src="{{ $partner->getImageUrl() }}" alt="{{ $partner->name ?? 'Partner Logo' }}">
                        </a>
                        @endforeach
                    </div><!-- End .owl-carousel -->
                </div><!-- End .container -->
            </div><!-- End .bg-lighter -->

            <div class="mb-6"></div><!-- End .mb-6 -->

            <div class="container">
                <div class="heading heading-center mb-3">
                    <h2 class="title-lg">{{ $homeSetting->trendy_product_title ?? 'Trendy Products' }}</h2><!-- End .title -->

                    <ul class="nav nav-pills justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="trendy-all-link" data-toggle="tab" href="#trendy-all-tab" role="tab" aria-controls="trendy-all-tab" aria-selected="true">All</a>
                        </li>
                        @foreach($trendy_categories as $category)
                        <li class="nav-item">
                            <a class="nav-link" id="trendy-{{ $category->category_slug }}-link" data-toggle="tab" href="#trendy-{{ $category->category_slug }}-tab" role="tab" aria-controls="trendy-{{ $category->category_slug }}-tab" aria-selected="false">{{ $category->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div><!-- End .heading -->

                <div class="tab-content tab-content-carousel">
                    <div class="tab-pane p-0 fade show active" id="trendy-all-tab" role="tabpanel" aria-labelledby="trendy-all-link">
                        <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                            data-owl-options='{
                                "nav": false, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
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
                            @foreach($getTrendyProducts as $product)
                                @include('product._single_trendy_card', ['product' => $product])
                            @endforeach
                        </div><!-- End .owl-carousel -->
                    </div><!-- .End .tab-pane -->

                    @foreach($trendy_categories as $category)
                    <div class="tab-pane p-0 fade" id="trendy-{{ $category->category_slug }}-tab" role="tabpanel" aria-labelledby="trendy-{{ $category->category_slug }}-link">
                        <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                            data-owl-options='{
                                "nav": false, 
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
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
                            @foreach($category->products as $product)
                                @include('product._single_trendy_card', ['product' => $product])
                            @endforeach
                        </div><!-- End .owl-carousel -->
                    </div><!-- .End .tab-pane -->
                    @endforeach
                </div><!-- End .tab-content -->
            </div><!-- End .container -->

    		<div class="container categories pt-6">
        		<h2 class="title-lg text-center mb-4">{{ $homeSetting->shop_category_title ?? 'Shop by Categories' }}</h2><!-- End .title-lg text-center -->

        		<div class="row justify-content-center">
                    @foreach($shop_categories as $cat)
        			<div class="col-6 col-md-4 col-lg-3 mb-3">
        				<div class="banner banner-display banner-link-anim" style="height: 260px; background-color: #eee; overflow: hidden; display: block; width: 100%;">
                			<a href="{{ url($cat->category_slug) }}" style="display: block; height: 100%; width: 100%;">
                				<img src="{{ $cat->getImageUrl() }}" alt="{{ $cat->name }}" style="height: 100% !important; width: 100% !important; object-fit: cover !important;">
                			</a>

                			<div class="banner-content banner-content-center">
                				<h3 class="banner-title text-white"><a href="{{ url($cat->category_slug) }}">{{ $cat->name }}</a></h3><!-- End .banner-title -->
                				<a href="{{ url($cat->category_slug) }}" class="btn btn-outline-white banner-link">{{ !empty($cat->button_text) ? $cat->button_text : 'Shop Now' }}<i class="icon-long-arrow-right"></i></a>
                			</div><!-- End .banner-content -->
            			</div><!-- End .banner -->
        			</div><!-- End .col -->
                    @endforeach
        		</div><!-- End .row -->
    		</div><!-- End .container -->

            <div class="mb-2"></div><!-- End .mb-6 -->

            
            <div class="container">
                <div class="heading heading-center mb-6">
                    <h2 class="title">{{ $homeSetting->recent_arrival_title ?? 'Recent Arrivals' }}</h2><!-- End .title -->

                    <ul class="nav nav-pills nav-border-anim justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="top-all-link" data-toggle="tab" href="#top-all-tab" role="tab" aria-controls="top-all-tab" aria-selected="true" data-category-id="0">All</a>
                        </li>
                        @foreach($recent_categories as $category)
                        <li class="nav-item">
                            <a class="nav-link" id="top-{{ $category->category_slug }}-link" data-toggle="tab" href="#top-{{ $category->category_slug }}-tab" role="tab" aria-controls="top-{{ $category->category_slug }}-tab" aria-selected="false" data-category-id="{{ $category->id }}">{{ $category->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div><!-- End .heading -->

                <div class="tab-content">
                    <div class="tab-pane p-0 fade show active" id="top-all-tab" role="tabpanel" aria-labelledby="top-all-link" data-category-id="0">
                        <div class="products">
                            <div class="row justify-content-center">
                                @foreach($recent_products as $product)
                                <div class="col-6 col-md-4 col-lg-3">
                                    @include('product._single_trendy_card', ['product' => $product])
                                </div>
                                @endforeach
                            </div><!-- End .row -->
                        </div><!-- End .products -->
                    </div><!-- .End .tab-pane -->

                    @foreach($recent_categories as $category)
                    <div class="tab-pane p-0 fade" id="top-{{ $category->category_slug }}-tab" role="tabpanel" aria-labelledby="top-{{ $category->category_slug }}-link" data-category-id="{{ $category->id }}">
                        <div class="products">
                            <div class="row justify-content-center">
                                @foreach($category->products as $product)
                                <div class="col-6 col-md-4 col-lg-3">
                                    @include('product._single_trendy_card', ['product' => $product])
                                </div>
                                @endforeach
                            </div><!-- End .row -->
                        </div><!-- End .products -->
                    </div><!-- .End .tab-pane -->
                    @endforeach
                </div><!-- End .tab-content -->
                <div class="more-container text-center">
                    <a href="#" class="btn btn-outline-darker btn-more btn-more-recent"><span>Load more products</span><i class="icon-long-arrow-down"></i></a>
                </div><!-- End .more-container -->
            </div><!-- End .container -->

            <div class="container">
                <hr>
            	<div class="row justify-content-center">
                    <div class="col-lg-4 col-sm-6">
                        <div class="icon-box icon-box-card text-center">
                            <span class="icon-box-icon">
                                @if(!empty($homeSetting->payment_delivery_image))
                                    <img src="{{ $homeSetting->getPaymentDeliveryImageUrl() }}" alt="" style="max-height: 40px; display: inline-block;">
                                @else
                                    <i class="icon-rocket"></i>
                                @endif
                            </span>
                            <div class="icon-box-content">
                                <h3 class="icon-box-title">{{ $homeSetting->payment_delivery_title ?? 'Payment & Delivery' }}</h3><!-- End .icon-box-title -->
                                <p>{{ $homeSetting->payment_delivery_description ?? 'Free shipping for orders over $50' }}</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-lg-4 col-sm-6 -->

                    <div class="col-lg-4 col-sm-6">
                        <div class="icon-box icon-box-card text-center">
                            <span class="icon-box-icon">
                                @if(!empty($homeSetting->refund_image))
                                    <img src="{{ $homeSetting->getRefundImageUrl() }}" alt="" style="max-height: 40px; display: inline-block;">
                                @else
                                    <i class="icon-rotate-left"></i>
                                @endif
                            </span>
                            <div class="icon-box-content">
                                <h3 class="icon-box-title">{{ $homeSetting->refund_title ?? 'Return & Refund' }}</h3><!-- End .icon-box-title -->
                                <p>{{ $homeSetting->refund_description ?? 'Free 100% money back guarantee' }}</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-lg-4 col-sm-6 -->

                    <div class="col-lg-4 col-sm-6">
                        <div class="icon-box icon-box-card text-center">
                            <span class="icon-box-icon">
                                @if(!empty($homeSetting->support_image))
                                    <img src="{{ $homeSetting->getSupportImageUrl() }}" alt="" style="max-height: 40px; display: inline-block;">
                                @else
                                    <i class="icon-life-ring"></i>
                                @endif
                            </span>
                            <div class="icon-box-content">
                                <h3 class="icon-box-title">{{ $homeSetting->support_title ?? 'Quality Support' }}</h3><!-- End .icon-box-title -->
                                <p>{{ $homeSetting->support_description ?? 'Alway online feedback 24/7' }}</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-lg-4 col-sm-6 -->
                </div><!-- End .row -->

                <div class="mb-2"></div><!-- End .mb-2 -->
            </div><!-- End .container -->
            <div class="blog-posts pt-7 pb-7" style="background-color: #fafafa;">
                <div class="container">
                   <h2 class="title-lg text-center mb-3 mb-md-4">{{ $homeSetting->blog_title ?? 'From Our Blog' }}</h2><!-- End .title-lg text-center -->

                    <div class="owl-carousel owl-simple carousel-with-shadow" data-toggle="owl" 
                        data-owl-options='{
                            "nav": false, 
                            "dots": true,
                            "items": 3,
                            "margin": 20,
                            "loop": false,
                            "responsive": {
                                "0": {
                                    "items":1
                                },
                                "600": {
                                    "items":2
                                },
                                "992": {
                                    "items":3
                                }
                            }
                        }'>
                        @foreach($getRecentBlogs as $blog)
                        <article class="entry entry-display">
                            <figure class="entry-media">
                                <a href="{{ url('blog/' . $blog->slug) }}">
                                    <img src="{{ $blog->getImageUrl() }}" alt="{{ $blog->title }}" style="height: 250px; width: 100%; object-fit: cover;">
                                </a>
                            </figure><!-- End .entry-media -->

                            <div class="entry-body pb-4 text-center">
                                <div class="entry-meta">
                                    <a href="#">{{ $blog->created_at->format('M d, Y') }}</a>, by {{ $blog->author->name ?? 'Admin' }}
                                </div><!-- End .entry-meta -->

                                <h3 class="entry-title">
                                    <a href="{{ url('blog/' . $blog->slug) }}">{{ $blog->title }}</a>
                                </h3><!-- End .entry-title -->

                                <div class="entry-content">
                                    <p>{{ Str::limit(strip_tags($blog->short_description), 100) }}</p>
                                    <a href="{{ url('blog/' . $blog->slug) }}" class="read-more">Read More</a>
                                </div><!-- End .entry-content -->
                            </div><!-- End .entry-body -->
                        </article><!-- End .entry -->
                        @endforeach
                    </div><!-- End .owl-carousel -->
                </div><!-- container -->

                <div class="more-container text-center mb-0 mt-3">
                    <a href="{{ url('blog') }}" class="btn btn-outline-darker btn-more"><span>View more articles</span><i class="icon-long-arrow-right"></i></a>
                </div><!-- End .more-container -->
            </div>
            <div class="cta cta-display bg-image pt-4 pb-4" style="background-image: url('{{ !empty($homeSetting->singup_image) ? $homeSetting->getSingupImageUrl() : asset('assets/images/backgrounds/cta/bg-6.jpg') }}');">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-9 col-xl-8">
                            <div class="row no-gutters flex-column flex-sm-row align-items-sm-center">
                                <div class="col">
                                    <h3 class="cta-title text-white">{{ $homeSetting->singup_title ?? 'Sign Up & Get 10% Off' }}</h3><!-- End .cta-title -->
                                    <p class="cta-desc text-white">{{ $homeSetting->singup_description ?? 'Molla presents the best in interior design' }}</p><!-- End .cta-desc -->
                                </div><!-- End .col -->

                                <div class="col-auto">
                                    <a href="#signin-modal" data-toggle="modal" class="btn btn-outline-white"><span>SIGN UP</span><i class="icon-long-arrow-right"></i></a>
                                </div><!-- End .col-auto -->
                            </div><!-- End .row no-gutters -->
                        </div><!-- End .col-md-10 col-lg-9 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .cta -->
        </main><!-- End .main -->

        
   @endsection

@section('script')
<script>
    $(document).ready(function() {
        // Load More button click
        $('.btn-more-recent').on('click', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var $activeTabLink = $('.nav-border-anim .nav-link.active');
            var category_id = $activeTabLink.data('category-id');
            var $activeTabPane = $('.tab-content .tab-pane.active.show');
            var $grid = $activeTabPane.find('.row');
            
            // Count current items in grid to use as offset
            var offset = $grid.find('.col-6').length;
            
            // Disable button during ajax request
            $btn.addClass('disabled').find('span').text('Loading...');
            
            $.ajax({
                url: "{{ url('recent-arrivals-load-more') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    category_id: category_id,
                    offset: offset
                },
                success: function(response) {
                    if (response.html) {
                        $grid.append(response.html);
                    }
                    
                    // Reset button text
                    $btn.removeClass('disabled').find('span').text('Load more products');
                    
                    // Show or hide button based on has_more flag
                    if (response.has_more) {
                        $btn.parent().show();
                    } else {
                        $btn.parent().hide();
                    }
                },
                error: function() {
                    alert('Something went wrong. Please try again.');
                    $btn.removeClass('disabled').find('span').text('Load more products');
                }
            });
        });

        // Hide or show the Load More button when switching tabs based on items count
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var $activeTabLink = $(e.target);
            // Check if it is a Recent Arrivals tab link
            if ($activeTabLink.attr('id') && $activeTabLink.attr('id').startsWith('top-')) {
                var category_id = $activeTabLink.data('category-id');
                var $activeTabPane = $($(e.target).attr('href'));
                var $grid = $activeTabPane.find('.row');
                var offset = $grid.find('.col-6').length;
                var $btn = $('.btn-more-recent');
                
                // Show load more during checking
                $btn.parent().show();
                $btn.addClass('disabled').find('span').text('Checking...');
                
                $.ajax({
                    url: "{{ url('recent-arrivals-load-more') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        category_id: category_id,
                        offset: offset
                    },
                    success: function(response) {
                        $btn.removeClass('disabled').find('span').text('Load more products');
                        if (response.has_more || response.html != '') {
                            // If there are more products to load, or the current offset is not full
                            $btn.parent().show();
                        } else {
                            $btn.parent().hide();
                        }
                    },
                    error: function() {
                        $btn.removeClass('disabled').find('span').text('Load more products');
                    }
                });
            }
        });
    });
</script>
@endsection