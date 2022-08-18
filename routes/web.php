<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

//お問合せフォーム
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'sendMail']);
Route::get('/contact/complete', [ContactController::class, 'complete'])->name('contact.complete');

//管理
Route::prefix('/admin')
    ->name('admin.')
    ->group(function(){
        //ログインのみアクセス可能なルート
        Route::middleware('auth')
        ->group(function(){
            //ブログ
            Route::resource('/blogs', AdminBlogController::class)->except('show');

            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
       //未ログインのみアクセス可能なルート
        Route::middleware('guest')
        ->group(function(){
            //認証
            Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [AuthController::class, 'login']);
        });
    });

 //ブログ
        // Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
        // Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('blogs.create');
        // Route::post('/blogs', [AdminBlogController::class, 'store'])->name('blogs.store');
        // Route::get('/blogs/{blog}', [AdminBlogController::class, 'edit'])->name('blogs.edit');
        // Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->name('blogs.update');
        // Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');

// //ユーザー登録
// Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
// Route::post('admin/users', [UserController::class, 'store'])->name('admin.users.store');



