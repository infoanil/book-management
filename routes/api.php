<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'verify.candidateToken'], function () {
    Route::get('authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::get('authors/{id}', [AuthorController::class, 'show'])->name('authors.show');
    Route::delete('authors/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy');
    Route::post('books', [BookController::class, 'store'])->name('books.store');
    Route::delete('books/{id}', [BookController::class, 'destroy'])->name('books.destroy');
});
