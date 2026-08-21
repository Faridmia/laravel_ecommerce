<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left">
                <div class="header-dropdown">
                    <a href="#">Usd</a>
                    <div class="header-menu">
                        <ul>
                            <li><a href="#">Usd</a></li>
                        </ul>
                    </div><!-- End .header-menu -->
                </div><!-- End .header-dropdown -->

                <div class="header-dropdown">
                    <a href="#">Eng</a>
                    <div class="header-menu">
                        <ul>
                            <li><a href="#">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="header-right">
                <ul class="top-menu">
                    <li>
                        <a href="#">Links</a>
                        <ul>
                            <li><a href="tel:#"><i class="icon-phone"></i>Call: +0123 456 789</a></li>
                            <li><a href="{{ route('wishlist') }}"><i class="icon-heart-o"></i>My Wishlist <span>({{ auth()->check() ? \App\Models\WishlistModel::where('user_id', auth()->id())->count() : 0 }})</span></a></li>
                            <li><a href="{{ route('compare') }}"><i class="icon-random"></i>Compare <span>({{ count(session()->get('compare', [])) }})</span></a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            @if(auth()->check())
                                <li><a href="{{ route('user.dashboard') }}"><i class="icon-user"></i>{{ auth()->user()->name }}</a></li>
                                <li><a href="{{ route('user.logout') }}"><i class="icon-user"></i>Logout</a></li>
                            @else
                                <li><a href="#signin-modal" data-toggle="modal"><i class="icon-user"></i>Login</a></li>
                            @endif
                        </ul>
                    </li>
                </ul><!-- End .top-menu -->
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-top -->

    <div class="header-middle sticky-header">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>

                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Molla Logo" width="105" height="25">
                </a>

                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        <li class="active">
                            <a href="{{ url('/') }}">Home</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="sf-with-ul">Shop</a>

                            <div class="megamenu megamenu-md">
                                <div class="row no-gutters">
                                    <div class="col-md-12">
                                        <div class="menu-col">
                                            <div class="row">
                                                @php
                                                    $categories = App\Models\Category::getCategoryMenu();
                                                @endphp

                                                @foreach ($categories as $category)
                                                 @if(!empty( $category->getSubCategory->count()))
                                                <div class="col-md-4" style="margin-bottom: 20px;">
                                                    <a href="{{ url( $category->category_slug ) }}" class="menu-title">{{ $category->name }}</a><!-- End .menu-title -->
                                                    <ul>
                                                        @foreach ($category->getSubCategory as $subCategory)
                                                        <li><a href="{{ url( $category->category_slug . '/' . $subCategory->category_slug) }}"> {{ $subCategory->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="header-right">
                <div class="header-search">
                    <a href="#" class="search-toggle" role="button" title="Search"><i class="icon-search"></i></a>
                    <form action="{{ url('search') }}" method="get">
                        <div class="header-search-wrapper">
                            <button for="q" class="sr-only">Search</button>
                            <input type="search" value="{{ !empty(Request::get('q')) ? Request::get('q') : '' }}" class="form-control" name="q" id="q" placeholder="Search in..." required>
                        </div>
                    </form>
                </div>

                <div class="dropdown cart-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        <i class="icon-shopping-cart"></i>
                        <span class="cart-count">{{ Cart::getTotalQuantity() }}</span>
                    </a>
                    @if( !empty( Cart::getContent()->count() ) )
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-cart-products">
                            @if(Cart::getContent()->count() > 0)
                                @foreach (Cart::getContent() as $headerCart)
                                @php
                                    $getProduct = App\Models\ProductModel::getSingle($headerCart->id);
                                    $getImage = App\Models\ProductImageModel::where('product_id', $headerCart->id)->orderBy('order_by', 'asc')->first();
                                    $imageSrc = $getImage ? $getImage->getImagesLogo() : null;
                                    $slug = $getProduct ? $getProduct->slug : '#';
                                    $title = $getProduct ? $getProduct->product_title : 'Product';
                                @endphp
                                <div class="product">
                                    <div class="product-cart-details">
                                        <h4 class="product-title">
                                            <a href="{{ url($slug) }}">{{ $title }}</a>
                                        </h4>

                                        <span class="cart-product-info">
                                            <span class="cart-product-qty">{{ $headerCart->quantity }}</span>
                                            x ${{ number_format($headerCart->price, 2) }}
                                        </span>
                                    </div><!-- End .product-cart-details -->

                                    <figure class="product-image-container">
                                        <a href="{{ url($slug) }}" class="product-image">
                                            <img src="{{ $imageSrc ? $imageSrc : '' }}" alt="{{ $title }}">
                                        </a>
                                    </figure>
                                    <a href="{{ url('cart/remove/'.$headerCart->id) }}" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a>
                                </div>

                                @endforeach
                            @else
                                <div class="product"><p class="cart-product-info">Your cart is empty.</p></div>
                            @endif
                        </div>

                        <div class="dropdown-cart-total">
                            <span>Total</span>

                            <span class="cart-total-price">${{ number_format(Cart::getTotal(), 2) }}</span>
                        </div><!-- End .dropdown-cart-total -->

                        <div class="dropdown-cart-action">
                            <a href="{{ url('cart') }}" class="btn btn-primary">View Cart</a>
                            <a href="{{ url('checkout') }}" class="btn btn-outline-primary-2"><span>Checkout</span><i class="icon-long-arrow-right"></i></a>
                        </div><!-- End .dropdown-cart-total -->
                    </div>
                    @endif
                </div><!-- End .cart-dropdown -->
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-middle -->
</header><!-- End .header -->