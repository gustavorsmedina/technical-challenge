<?php

use App\Http\Controllers\{UserController, WalletController};

Route::get('ping', fn () => 'pong');

Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::post('wallets', [WalletController::class, 'store'])->name('wallets.store');
Route::get('wallets/{wallet}', [WalletController::class, 'show'])->name('wallets.show');
