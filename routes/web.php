<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('requests', RequestController::class)
        ->parameters(['requests' => 'requestModel'])
        ->except(['update']);

    Route::post('requests/{requestModel}', [RequestController::class,'update'])->name('requests.update');
    Route::post('requests/{requestModel}/submit', [RequestController::class,'submit'])->name('requests.submit');

    Route::get('approvals/inbox', [ApprovalController::class,'inbox'])->name('approvals.inbox');
    Route::post('approvals/{requestModel}/approve', [ApprovalController::class,'approve'])->name('approvals.approve');
    Route::post('approvals/{requestModel}/reject',  [ApprovalController::class,'reject'])->name('approvals.reject');

    Route::post('trash/{id}/restore', [RequestController::class,'restore'])->name('requests.restore');
});

require __DIR__.'/auth.php';
