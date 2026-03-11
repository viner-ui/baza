<?php

use App\Http\Controllers\Api\MasterApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\RepairRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isDispatcher()
            ? redirect()->route('dispatcher.index')
            : redirect()->route('master.index');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/requests/create', [RepairRequestController::class, 'create'])->name('requests.create');
Route::post('/requests', [RepairRequestController::class, 'store'])->name('requests.store');

Route::middleware('auth')->group(function () {
    Route::get('/dispatcher', [DispatcherController::class, 'index'])->name('dispatcher.index')
        ->middleware('role:dispatcher');
    Route::post('/dispatcher/assign', [DispatcherController::class, 'assign'])->name('dispatcher.assign')
        ->middleware('role:dispatcher');
    Route::post('/dispatcher/requests/{id}/cancel', [DispatcherController::class, 'cancel'])->name('dispatcher.cancel')
        ->middleware('role:dispatcher');

    Route::get('/master', [MasterController::class, 'index'])->name('master.index')
        ->middleware('role:master');
    Route::post('/master/requests/{id}/take', [MasterController::class, 'takeInWork'])->name('master.take')
        ->middleware('role:master');
    Route::post('/master/requests/{id}/complete', [MasterController::class, 'complete'])->name('master.complete')
        ->middleware('role:master');

    Route::post('/api/requests/take', [MasterApiController::class, 'takeInWork'])->name('api.requests.take')
        ->middleware('role:master');
});
