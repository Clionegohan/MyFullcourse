<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryLikeController;
use App\Http\Controllers\DiaryCommentController;
use App\Http\Controllers\UserDiaryController;
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
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['verified'])->name('dashboard');

Route::controller(PostController::class)->middleware(['auth'])->group(function(){
    Route::get('/', 'index')->name('index');
    Route::get('/posts/create', 'create')->name('create');
    Route::post('/posts', 'store')->name('store');
    Route::get('/posts/{post}/edit', 'edit')->name('edit');
    Route::get('/posts/{post}', 'show')->name('posts.show');
    Route::put('/posts/{post}', 'update')->name('update');
    Route::delete('/posts/{post}', 'delete')->name('delete');
});

Route::controller(UserController::class)->middleware(['auth'])->group(function(){
    Route::get('/users/{user}/edit', 'edit')->name('users.edit');
    Route::get('/users/{user}', 'show')->name('show');
    Route::patch('/users/{user}', 'update')->name('users.update');
    
});

Route::controller(CommentController::class)->middleware(['auth'])->group(function(){
    Route::post('/comments', 'store')->name('comments.store');
    Route::delete('/comments/{comment}', 'delete')->name('comments.delete');
});

Route::get('/categories/{category}', [CategoryController::class, 'index'])->middleware("auth")->name('categories.index');

Route::get('/about', [HomeController::class, 'about'])->middleware("auth")->name('about');

Route::controller(SearchController::class)->middleware(['auth'])->group(function(){
    Route::get('/search', 'search')->name('search');
});

Route::controller(LikeController::class)->middleware(['auth'])->group(function(){
    Route::post('/like/{post}', 'like')->name('like.post');
    Route::get('/likes', 'index')->name('likes.index');
});

Route::controller(DiaryController::class)->middleware(['auth'])->group(function(){
    Route::get('/diaries', 'index')->name('diary.index');
    Route::get('/diaries/create', 'create')->name('diary.create');
    Route::post('/diaries', 'store')->name('diary.store');
    Route::get('/diaries/{diary}/edit', 'edit')->name('diary.edit');
    Route::get('/diaries/{diary}', 'show')->name('diary.show');
    Route::put('/diaries/{diary}', 'update')->name('diary.update');
    Route::delete('/diaries/{diary}', 'delete')->name('diary.delete');
});

Route::controller(DiaryLikeController::class)->middleware(['auth'])->group(function(){
    Route::post('/diaries/like/{diary}', 'like')->name('diary.like');
    Route::get('/diary/likes', 'index')->name('diary.likes.index');
});

Route::controller(DiaryCommentController::class)->middleware(['auth'])->group(function(){
    Route::post('/diaries/{diary}/comments', 'store')->name('diary.comments.store');
    Route::delete('/diaries/comments/{comment}', 'destroy')->name('diary.comments.destroy');
});

Route::controller(UserDiaryController::class)->middleware(['auth'])->group(function(){
    Route::get('/users/{user}/diaries', 'index')->name('user.diaries.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
