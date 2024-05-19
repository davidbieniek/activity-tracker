<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware('auth')->group(function () {
    // Route'y dla profilu użytkownika
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route'y dla kategorii
     // Określamy, że pod 'category.index' kryję się logika która jest zawarta w funckji index w CategoryController
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');

    Route::post('/activity/{category}', [ActivityController::class, 'store'])->name('activity.store');
    Route::delete('/activity/{activity}', [ActivityController::class, 'destroy'])->name('activity.destroy');

    //krótsza wersja usuwania kategorii
    // Route::delete('/category/{category}', [CategoryController::class, 'destroy2'])->name('category.destroy');

});


require __DIR__.'/auth.php';
