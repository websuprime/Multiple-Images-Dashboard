<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', [ImageController::class, 'index'])->name('home');

Route::prefix('admin')->group(function () {
    // Image Routes
    Route::get('images', [ImageController::class, 'index'])->name('images.index');
    Route::get('images/create', [ImageController::class, 'create'])->name('images.create');
    Route::post('images', [ImageController::class, 'store'])->name('images.store');
    Route::get('images/{id}/edit', [ImageController::class, 'edit'])->name('images.edit');
    Route::put('images/{id}', [ImageController::class, 'update'])->name('images.update');
    Route::get('images/{id}/preview', [ImageController::class, 'preview'])->name('images.preview');
    Route::delete('images/{id}', [ImageController::class, 'destroy'])->name('images.destroy');
    Route::post('images/bulk-delete', [ImageController::class, 'bulkDelete'])->name('images.bulkDelete');
});
