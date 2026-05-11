<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;

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

Route::get('/', function () {
    return view('welcome');
});

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
});
