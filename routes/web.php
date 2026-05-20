<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('documents', DocumentController::class);

    Route::get('/documents/{document}/history', [DocumentController::class, 'history'])
        ->name('documents.history');

    Route::post('/documents/{document}/revisions/{revision}/restore', [DocumentController::class, 'restore'])
        ->name('documents.revisions.restore');

    Route::post('/documents/{document}/share', [DocumentController::class, 'share'])
        ->name('documents.share');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';