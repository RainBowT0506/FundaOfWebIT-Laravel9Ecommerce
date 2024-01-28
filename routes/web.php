<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SliderController;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);


    //  Catrgory Routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'index');
        Route::get('/category/create', 'create');
        Route::post('/category/store ', 'store');
        Route::get('/category/{category}/edit ', 'edit');
        Route::put('/category/{category} ', 'update');
    });

    //  Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index');
        Route::get('/product/create', 'create');
        Route::post('/product', 'store');
        Route::get('/product/{product}/edit', 'edit');
        Route::put('/product/{product}', 'update');
        Route::get('/product/{product_id}/delete', 'destroy');

        Route::get('product-image/{product_image_id}/delete', 'destroyImage');

        Route::post('/product-color/{prod_color_id}', 'updateProdColorQty');
        Route::get('/product-color/{prod_color_id}/delete', 'deleteProdColor');
    });

    Route::get('brands', App\Livewire\Admin\Brand\Index::class);

    // Color
    Route::controller(ColorController::class)->group(function () {
        Route::get('/color', 'index');
        Route::get('/color/create', 'create');
        Route::post('/color/store ', 'store');
        Route::get('/color/{color}/edit', 'edit');
        Route::put('/color/{color_id}', 'update');
        Route::get('/color/{color_id}/delete', 'destroy');
    });

    Route::controller(SliderController::class)->group(function () {
        Route::get('slider', 'index');
        Route::get('slider/create', 'create');
        Route::post('slider/store', 'store');
        Route::get('slider/{slider}/edit', 'edit');
        Route::put('slider/{slider}', 'update');
        Route::get('slider/{slider}/delete', 'destroy');
    });
});
