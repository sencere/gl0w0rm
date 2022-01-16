<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ResultController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::post('/posts/options/{post_id}', [PostsController::class, 'getPostOptions']);
    Route::get('/posts/create', [PostsController::class, 'create']);
    Route::get('/posts/{post}', [PostsController::class, 'show']);
    Route::post('/posts', [PostsController::class, 'store']);
    Route::post('/predictions', [PredictionController::class, 'store']);
    Route::post('/posts/predictions/{post_id}', [PostsController::class, 'getPostPredictions']);
    Route::post('/results', [ResultController::class, 'store']);
    Route::post('/results/result/{post_id}', [ResultController::class, 'getResult']);
});
