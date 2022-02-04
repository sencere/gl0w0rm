<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChannelSettingsController;
use App\Http\Controllers\ChannelSubscriptionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;

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
Route::get('/reload-captcha', [App\Http\Controllers\Auth\RegisterController::class, 'reloadCaptcha']);

Route::get('/subscription/{channel}', 'ChannelSubscriptionController@show');
Route::get('/channel/{channel}', [ChannelController::class, 'show']);

Route::get('/search', [SearchController::class, 'index']);

Route::get('medium/{filename}', function ($filename)
{
    $path = storage_path('uploads/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::group(['middleware' => ['auth']], function () {
    // API
    Route::post('/posts/options/{post_id}', [PostsController::class, 'getPostOptions']);
    Route::post('/posts/predictions/{post_id}', [PostsController::class, 'getPostPredictions']);
    Route::post('/results/result/{post_id}', [ResultController::class, 'getResult']);
    Route::post('/results', [ResultController::class, 'store']);
    Route::post('/predictions', [PredictionController::class, 'store']);
    // END API

    Route::get('/posts/index', [PostsController::class, 'index']);
    Route::get('/posts/create', [PostsController::class, 'create']);
    Route::get('/posts/{post}', [PostsController::class, 'show']);

    Route::get('/topic/create', [TopicController::class, 'create']);
    Route::post('/topics', [TopicController::class, 'store']);

    Route::get('/user/{user}', [UserController::class, 'show']);

    // Navigation
    Route::get('/category/{name}', [CategoryController::class, 'index']);
    Route::get('/topic/{id}', [TopicController::class, 'index']);
    Route::post('/posts', [PostsController::class, 'store']);

    Route::get('/channel/{channel}/edit', [ChannelSettingsController::class, 'edit']);
    Route::put('/channel/{channel}/edit', [ChannelSettingsController::class, 'update']);

    Route::post('/subscription/{channel}', [ChannelSubscriptionController::class, 'create']);
    Route::delete('/subscription/{channel}', [ChannelSubscriptionController::class, 'delete']);

    Route::post('/subscription/status/{post_id}', [ChannelSubscriptionController::class, 'getSubscriptionStatus']);
});
