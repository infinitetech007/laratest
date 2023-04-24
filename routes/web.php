<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\Authenticate;

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

// Route::get('/login', function () {
//     return view('welcome');
// });

Auth::routes();

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
});

// Auth Route
Route::middleware([Authenticate::class])->group(function() {
    Route::controller(UsersController::class)->group(function() {
        Route::get('/users', 'index')->name('users');
        Route::post('/users/add-users', 'addUsers')->name('add-users');
        Route::delete('/users/delete-users/{id}', 'deleteUsers')->name('delete-users');
        Route::get('/users/{id}/edit-users', 'editUsers')->name('edit-users');
    });

    Route::controller(CategoryController::class)->group(function() {
        Route::get('/category', 'index')->name('category');
        Route::post('/category/add-category', 'addCategory')->name('add-category');
        Route::delete('/category/delete-category/{id}', 'deleteCategory')->name('delete-category');
        Route::get('/category/{id}/edit-category', 'editCategory')->name('edit-category');
    });

    Route::controller(ProductController::class)->group(function() {
        Route::get('/product', 'index')->name('product');
        Route::post('/product/add-product', 'addProduct')->name('add-product');
        Route::delete('/product/delete-product/{id}', 'deleteProduct')->name('delete-product');
        Route::get('/product/{id}/edit-product', 'editProduct')->name('edit-product');
    });
});