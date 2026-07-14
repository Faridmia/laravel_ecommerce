 @extends('layouts.app')
 @section('style')
 @endsection
 @section('content')
 
    <main class="main">
        <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
            <div class="container">
                <h1 class="page-title">Shopping Cart<span>Shop</span></h1>
            </div><!-- End .container -->
        </div><!-- End .page-header -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav --> 

        <div class="page-content">
            <div class="cart">
                <div class="container">
                    @if(Cart::getContent()->count() > 0)
                    <div class="row">
                        <div class="col-lg-9">
                           
                            <form action="{{ url('cart/update') }}" method="POST">
                                @csrf
                                <table class="table table-cart table-mobile">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach (Cart::getContent() as $item)
                                        @php
                                            $product = App\Models\ProductModel::getSingle($item->id);
                                            $image = App\Models\ProductImageModel::where('product_id', $item->id)->orderBy('order_by', 'asc')->first();
                                            $imageSrc = $image ? $image->getImagesLogo() : null;
                                            $slug = $product ? $product->slug : '#';
                                            $title = $product ? $product->product_title : 'Product';
                                        @endphp
                                        <tr>
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="{{ url($slug) }}">
                                                            <img src="{{ $imageSrc ? $imageSrc : 'assets/images/products/table/product-1.jpg' }}" alt="{{ $title }}">
                                                        </a>
                                                    </figure>

                                                    <h3 class="product-title">
                                                        <a href="{{ url($slug) }}">{{ $title }}</a>
                                                    </h3><!-- End .product-title -->
                                                </div><!-- End .product -->
                                            </td>
                                            <td class="price-col">${{ number_format($item->price, 2) }}</td>
                                            <td class="quantity-col">
                                                <div class="cart-product-quantity">
                                                    <input type="number" name="qty[{{ $item->id }}]" class="form-control" value="{{ $item->quantity }}" min="1" max="100" step="1" data-decimals="0" required>
                                                </div><!-- End .cart-product-quantity -->
                                            </td>
                                            <td class="total-col">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                            <td class="remove-col"><a href="{{ url('cart/remove/'.$item->id) }}" class="btn-remove"><i class="icon-close"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                           
                        </div><!-- End .col-lg-9 -->
                        <aside class="col-lg-3">
                            <div class="summary summary-cart">
                                <h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->

                                <table class="table table-summary">
                                    <tbody>
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td>${{ number_format(Cart::getSubTotal(), 2) }}</td>
                                        </tr><!-- End .summary-subtotal -->
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td>${{ number_format(Cart::getTotal(), 2) }}</td>
                                        </tr><!-- End .summary-total -->
                                    </tbody>
                                </table><!-- End .table table-summary -->

                                <a href="{{ route('checkout') }}" class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO CHECKOUT</a>
                            </div><!-- End .summary -->

                            <a href="{{ url('/') }}" class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE SHOPPING</span><i class="icon-refresh"></i></a>
                        </aside><!-- End .col-lg-3 -->
                    </div><!-- End .row -->
                     @else
                        <div class="text-center py-5">
                            <h3>Your cart is empty.</h3>
                            <a href="{{ url('/') }}" class="btn btn-outline-primary-2 mt-2"><span>CONTINUE SHOPPING</span></a>
                        </div>
                    @endif
                </div><!-- End .container -->
            </div><!-- End .cart -->
        </div><!-- End .page-content -->
    </main><!-- End .main -->

@endsection
@section('script')
@endsection