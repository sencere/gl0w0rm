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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostVoteController;

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
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/reload-captcha', [App\Http\Controllers\Auth\RegisterController::class, 'reloadCaptcha']);

Route::get('/subscription/{channel}', 'ChannelSubscriptionController@show');
Route::get('/channel/{channel}', [ChannelController::class, 'show']);

Route::get('/search', [SearchController::class, 'index']);
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/admin/user', [AdminController::class, 'user']);
Route::get('/admin/post', [AdminController::class, 'post']);

Route::get('/admin/category', [AdminController::class, 'category']);
Route::post('/category', [CategoryController::class, 'store']);



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
    Route::get('/posts/delete/{id}', [PostsController::class, 'delete']);

    Route::get('/topic/create', [TopicController::class, 'create']);
    Route::post('/topics', [TopicController::class, 'store']);

    Route::get('/user/{user}', [UserController::class, 'show']);

    // Navigation
    Route::get('/category/{name}', [CategoryController::class, 'index']);
    Route::get('/topic/{id}/{page}', [TopicController::class, 'index']);

    Route::post('/posts', [PostsController::class, 'store']);
    Route::post('/posts/{post}/comment', [CommentController::class, 'store']);

    Route::post('/posts/{post}/votes', [PostVoteController::class, 'create']);
    Route::delete('/posts/{post}/votes', [PostVoteController::class, 'remove']); 

    Route::get('/channel/{channel}/edit', [ChannelSettingsController::class, 'edit']);
    Route::put('/channel/{channel}/edit', [ChannelSettingsController::class, 'update']);

    Route::post('/subscription/{channel}', [ChannelSubscriptionController::class, 'create']);
    Route::delete('/subscription/{channel}', [ChannelSubscriptionController::class, 'delete']);

    Route::post('/subscription/status/{post}', [ChannelSubscriptionController::class, 'getSubscriptionStatus']);

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
