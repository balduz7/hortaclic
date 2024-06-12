<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MailController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PlaceController;
use App\Http\Middleware\EnsureUserHasRoles;

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

Route::get('/dashboard', function (Request $request) {
    //$request->session()->flash('info', 'TEST flash messages');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/language/{locale}', [LanguageController::class, 'language'])->name('language');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('mail/test', [MailController::class, 'test']);

require __DIR__.'/auth.php';

Route::resource('files', FileController::class)->middleware(['auth']);
Route::get('files/{file}/delete', [FileController::class, 'delete'])->name('files.delete')->middleware(['auth']);

Route::resource('posts', PostController::class)->middleware(['auth']);

Route::controller(PostController::class)->group(function () {
    Route::get('posts/{post}/delete', 'delete')->name('posts.delete');
    Route::post('/posts/{post}/likes', 'like')->name('posts.like');
    Route::delete('/posts/{post}/likes', 'unlike')->name('posts.unlike');
});

Route::resource('places', PlaceController::class)->middleware(['auth']);

Route::controller(PlaceController::class)->group(function () {
    Route::get('places/{place}/delete', 'delete')->name('places.delete');
    Route::post('/places/{place}/favs', 'favorite')->name('places.favorite');
    Route::delete('/places/{place}/favs', 'unfavorite')->name('places.unfavorite');
});

