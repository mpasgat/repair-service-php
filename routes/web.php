<?php

use App\Http\Controllers\AuthChoiceController;
use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PublicRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('requests.create'));

Route::get('/requests/create', [PublicRequestController::class, 'create'])->name('requests.create');
Route::post('/requests', [PublicRequestController::class, 'store'])->name('requests.store');

Route::get('/login', [AuthChoiceController::class, 'create'])->name('login');
Route::post('/login', [AuthChoiceController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthChoiceController::class, 'destroy'])->name('logout');

Route::middleware(['auth', 'role:dispatcher'])->prefix('dispatcher')->name('dispatcher.')->group(function (): void {
    Route::get('/', [DispatcherController::class, 'index'])->name('index');
    Route::post('/requests/{repairRequest}/assign', [DispatcherController::class, 'assign'])->name('assign');
    Route::post('/requests/{repairRequest}/cancel', [DispatcherController::class, 'cancel'])->name('cancel');
});

Route::middleware(['auth', 'role:master'])->prefix('master')->name('master.')->group(function (): void {
    Route::get('/', [MasterController::class, 'index'])->name('index');
    Route::post('/requests/{repairRequest}/take', [MasterController::class, 'take'])->name('take');
    Route::post('/requests/{repairRequest}/complete', [MasterController::class, 'complete'])->name('complete');
});
