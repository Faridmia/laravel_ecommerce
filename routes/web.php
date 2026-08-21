<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as ProductFront;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [HomeController::class, 'home']);

Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/search', [ProductController::class, 'search'])->name('search');


Route::get('/admin', [AuthController::class, 'login_admin'])->name('admin.login');
Route::post('/admin', [AuthController::class, 'auth_login_admin']);

Route::middleware(['web'])->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout_admin'])->name('admin.logout');
});

Route::middleware(['web', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/admin/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('admin.dashboard');

    Route::get('/admin/admin/list', [AdminController::class, 'list'])->name('admin.admin.list');

    Route::get('/admin/admin/add', [AdminController::class, 'create'])->name('admin.admin.add');
    Route::post('/admin/admin/store', [AdminController::class, 'store'])->name('admin.admin.store');

    Route::get('/admin/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.admin.edit');
    Route::put('/admin/admin/update/{id}', [AdminController::class, 'update'])->name('admin.admin.update');
    Route::get('/admin/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.admin.delete');

    // category routes

    Route::get('/admin/category/list', [CategoryController::class, 'list'])->name('admin.category.list');
    Route::get('/admin/category/add', [CategoryController::class, 'create'])->name('admin.category.add');
    Route::post('/admin/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/admin/category/update/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::get('/admin/category/delete/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');

    // sub category routes
    Route::get('/admin/subcategory/list', [SubCategoryController::class, 'list'])->name('admin.subcategory.list');
    Route::get('/admin/subcategory/add', [SubCategoryController::class, 'create'])->name('admin.subcategory.add');
    Route::post('/admin/subcategory/store', [SubCategoryController::class, 'store'])->name('admin.subcategory.store');
    Route::get('/admin/subcategory/edit/{id}', [SubCategoryController::class, 'edit'])->name('admin.subcategory.edit');
    Route::put('/admin/subcategory/update/{id}', [SubCategoryController::class, 'update'])->name('admin.subcategory.update');
    Route::get('/admin/subcategory/delete/{id}', [SubCategoryController::class, 'delete'])->name('admin.subcategory.delete');

    // product routes
    Route::get('/admin/product/list', [ProductController::class, 'list'])->name('admin.product.list');
    Route::get('/admin/product/add', [ProductController::class, 'create'])->name('admin.product.add');
    Route::post('/admin/product/store', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::put('/admin/product/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::get('/admin/product/delete/{id}', [ProductController::class, 'delete'])->name('admin.product.delete');
    Route::post('/admin/product/get-sub-category', [ProductController::class, 'getSubCategory'])
    ->name('admin.product.get_sub_category');
    Route::get('/admin/product/image/{id}', [ProductController::class, 'deleteImage'])
    ->name('admin.product.delete_image');

    Route::post('/admin/product/update-image-order', [ProductController::class, 'ProductImageOrder'])
    ->name('admin.product.update_image_order');


    Route::get('/admin/brand/list', [ BrandController::class, 'list'])->name('admin.brand.list');
    Route::get('/admin/brand/add', [ BrandController::class, 'create'])->name('admin.brand.add');
    Route::post('/admin/brand/store', [ BrandController::class, 'store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [BrandController::class, 'edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update/{id}', [BrandController::class, 'update'])->name('admin.brand.update');
    Route::get('/admin/brand/delete/{id}', [BrandController::class, 'delete'])->name('admin.brand.delete');


    Route::get('/admin/color/list', [ColorController::class, 'list'])->name('admin.color.list');
    Route::get('/admin/color/add', [ColorController::class, 'create'])->name('admin.color.add');
    Route::post('/admin/color/store', [ColorController::class, 'store'])->name('admin.color.store');
    Route::get('/admin/color/edit/{id}', [ColorController::class, 'edit'])->name('admin.color.edit');
    Route::put('/admin/color/update/{id}', [ColorController::class, 'update'])->name('admin.color.update');
    Route::get('/admin/color/delete/{id}', [ColorController::class, 'delete'])->name('admin.color.delete');

    // coupon routes
    Route::get('/admin/coupon/list', [CouponController::class, 'list'])->name('admin.coupon.list');
    Route::get('/admin/coupon/add', [CouponController::class, 'create'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [CouponController::class, 'store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/edit/{id}', [CouponController::class, 'edit'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update/{id}', [CouponController::class, 'update'])->name('admin.coupon.update');
    Route::get('/admin/coupon/delete/{id}', [CouponController::class, 'delete'])->name('admin.coupon.delete');

    // shipping zones & locations
    Route::get('/admin/shipping/zones', [ShippingZoneController::class, 'list'])->name('admin.shipping.zones.list');
    Route::get('/admin/shipping/zones/add', [ShippingZoneController::class, 'create'])->name('admin.shipping.zones.add');
    Route::post('/admin/shipping/zones/store', [ShippingZoneController::class, 'store'])->name('admin.shipping.zones.store');
    Route::get('/admin/shipping/zones/edit/{id}', [ShippingZoneController::class, 'edit'])->name('admin.shipping.zones.edit');
    Route::put('/admin/shipping/zones/update/{id}', [ShippingZoneController::class, 'update'])->name('admin.shipping.zones.update');
    Route::get('/admin/shipping/zones/delete/{id}', [ShippingZoneController::class, 'delete'])->name('admin.shipping.zones.delete');
    Route::get('/admin/shipping/locations/{location_id}/delete', [ShippingZoneController::class, 'deleteLocation'])->name('admin.shipping.locations.delete');
    Route::post('/admin/shipping/zones/{zone_id}/locations/store', [ShippingZoneController::class, 'storeLocation'])->name('admin.shipping.locations.store');

    // shipping methods & rates
    Route::post('/admin/shipping/zones/{zone_id}/methods/store', [ShippingZoneController::class, 'storeMethod'])->name('admin.shipping.methods.store');
    Route::put('/admin/shipping/methods/{method_id}/update', [ShippingZoneController::class, 'updateMethod'])->name('admin.shipping.methods.update');
    Route::put('/admin/shipping/methods/{method_id}/update-form', [ShippingZoneController::class, 'updateMethodForm'])->name('admin.shipping.methods.update_form');
    Route::get('/admin/shipping/methods/{method_id}/delete', [ShippingZoneController::class, 'deleteMethod'])->name('admin.shipping.methods.delete');
    Route::post('/admin/shipping/methods/{method_id}/rates/store', [ShippingZoneController::class, 'storeRate'])->name('admin.shipping.rates.store');
    Route::put('/admin/shipping/rates/{rate_id}/update', [ShippingZoneController::class, 'updateRate'])->name('admin.shipping.rates.update');
    Route::get('/admin/shipping/rates/{rate_id}/delete', [ShippingZoneController::class, 'deleteRate'])->name('admin.shipping.rates.delete');

    // order management routes
    Route::get('/admin/orders/list', [\App\Http\Controllers\Admin\OrderController::class, 'list'])->name('admin.orders.list');
    Route::get('/admin/orders/show/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/update/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('admin.orders.update');
    Route::get('/admin/orders/delete/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'delete'])->name('admin.orders.delete');

    // settings routes
    Route::get('/admin/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings/update', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

});


Route::post('get_filter_products_ajax', [ProductFront::class, 'getFilterProductAjax']);

Route::get('cart', [PaymentController::class, 'cart'])->name('cart');
Route::post('product/add-to-cart', [PaymentController::class, 'addToCart']);
Route::post('cart/update', [PaymentController::class, 'updateCart'])->name('cart.update');
Route::get('cart/remove/{id}', [PaymentController::class, 'removeFromCart'])->name('cart.remove');

Route::get('checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::post('coupon/apply', [PaymentController::class, 'applyCoupon'])->name('coupon.apply');
Route::post('checkout/place', [PaymentController::class, 'placeOrder'])->name('checkout.place');
Route::get('checkout/success/{id}', [PaymentController::class, 'orderSuccess'])->name('checkout.success');

// location & shipping calc routes
Route::get('locations/divisions/{country_id}', [ShippingController::class, 'getDivisions']);
Route::get('locations/districts/{division_id}', [ShippingController::class, 'getDistricts']);
Route::get('locations/areas/{district_id}', [ShippingController::class, 'getAreas']);
Route::post('checkout/shipping-rates', [ShippingController::class, 'calculateRates'])->name('checkout.shipping_rates');
Route::post('checkout/select-shipping-rate', [ShippingController::class, 'selectShippingRate'])->name('checkout.select_shipping_rate');

Route::get('shop', [ProductFront::class, 'shop'])->name('shop');

Route::get('search', [ProductFront::class, 'getProductSearch'])->name('search');
Route::post('product/review', [ProductFront::class, 'submitReview'])->name('product.review.submit');

// customer auth routes
Route::post('user/register', [AuthController::class, 'userRegister'])->name('user.register');
Route::post('user/login', [AuthController::class, 'userLogin'])->name('user.login');
Route::get('user/logout', [AuthController::class, 'userLogout'])->name('user.logout');

// customer dashboard routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('user/dashboard', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('user.dashboard');
    Route::post('user/profile/update', [\App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('user/orders/show/{id}', [\App\Http\Controllers\CustomerController::class, 'showOrder'])->name('user.orders.show');

    // wishlist routes
    Route::get('wishlist', [\App\Http\Controllers\HomeController::class, 'wishlist'])->name('wishlist');
    Route::get('wishlist/add/{product_id}', [\App\Http\Controllers\HomeController::class, 'addToWishlist'])->name('wishlist.add');
    Route::get('wishlist/remove/{id}', [\App\Http\Controllers\HomeController::class, 'removeFromWishlist'])->name('wishlist.remove');
});

// product compare routes
Route::get('compare', [\App\Http\Controllers\HomeController::class, 'compare'])->name('compare');
Route::get('compare/add/{product_id}', [\App\Http\Controllers\HomeController::class, 'addToCompare'])->name('compare.add');
Route::get('compare/remove/{product_id}', [\App\Http\Controllers\HomeController::class, 'removeFromCompare'])->name('compare.remove');

// social login routes
Route::get('auth/google', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'handleFacebookCallback']);

// Wildcard route (must be at the bottom)
Route::get('{category?}/{subcategory?}', [ProductFront::class, 'getCategorySub']);


