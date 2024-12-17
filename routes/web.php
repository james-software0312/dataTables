<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;

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


Route::middleware('guest')->group(function () {
    Route::get('/registration', [AuthenController::class, 'registration']);
    Route::post('/registration-user', [AuthenController::class, 'registerUser'])->name("register-user");
    Route::get('/login', [AuthenController::class, 'login'])->name("login");
    Route::post('/login-user', [AuthenController::class, 'loginUser'])->name("login-user");
    
    Route::get('/forgot-password', [AuthenController::class, 'forgotPassword'])->name("forgot-password");
    Route::post('/forgot-password', [AuthenController::class, 'forgotPassword_act']);

    
    Route::get('reset-password/{token}', [AuthenController::class, 'reset_password'])->name('password.reset');
    Route::post('reset-password', [AuthenController::class, 'reset_password_act'])->name('password.update');
    Route::post('verify', [AuthenController::class, 'verifyAccount'])->name('verify');
    Route::get('verifying/{token}', [AuthenController::class, 'verifying_g'])->name('verifying');

});
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name("dashboard");
    Route::get('/home', [HomeController::class, 'index'])->name("home");
    Route::get('/logout',[AuthenController::class, 'logout']);
    Route::post('/article.insert', [ArticleController::class, 'insert'] ) ->name('article.insert');
    Route::get('/articles.get', [ArticleController::class, 'getArticles'] ) ->name('articles.get');
    Route::get('/article.get', [ArticleController::class, 'getArticle'] ) ->name('article.get');
    Route::delete('/article.delete', [ArticleController::class, 'deleteArticle'] ) ->name('article.delete');
});