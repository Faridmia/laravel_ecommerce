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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as ProductFront;

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

Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
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

});


Route::post('get_filter_products_ajax', [ProductFront::class, 'getFilterProductAjax']);
Route::get('{category?}/{subcategory?}', [ProductFront::class, 'getCategorySub']);
