<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/depth-chart', function () {
    return view('IBL/depth-chart.index');
})->middleware(['auth', 'verified'])->name('depth-chart');

Route::post('/depth-chart/submit', function () {
    return view('IBL/depth-chart/submit', request()->all());
})->name('depth-chart/submit');

Route::get('/leaguestats', function () {
    return view('IBL/leaguestats');
})->name('leaguestats');

Route::get('/upcomingfreeagency', function () {
    return view('IBL/upcomingfreeagency');
})->name('upcomingfreeagency');

Route::get('/helloibl', function () {
    return view('IBL/helloibl');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
