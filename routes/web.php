<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MpesaCallbackController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\WithdrawalRequestController;

// Home
Route::get('/', function () {
    return view('index');
});

Route::get('/offline', function () {
    return view('offline');
});

// ─── Guest Routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('login'))->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', fn() => view('register'))->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [GroupController::class, 'dashboard'])->name('dashboard');
    Route::get('/create-group', fn() => view('create-group'));
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/join-group', fn() => view('join-group'));

 
    Route::get('/groups', [GroupController::class, 'index']);

    Route::get('/groups/{id}', [GroupController::class, 'show']);

    Route::get('/groups/{id}/transactions', fn($id) => view('transactions', ['id' => $id]))
        ->name('transactions.page');

    Route::get('/groups/{id}/transactions-page', [PageController::class, 'transactionsPage'])
        ->name('group.transactions.page');

    Route::get('/groups/{id}/withdraw', fn() => view('withdraw'));

    Route::get('/groups/{id}/withdrawals/admin', fn($id) => view('withdrawals-admin', ['groupId' => $id]))
        ->name('withdrawals.admin');

    // M-Pesa
    Route::post('/groups/{group}/contribute', [MpesaCallbackController::class, 'contribute'])
        ->name('mpesa.contribute');
});

// ─── M-Pesa Callback (no auth — Safaricom posts here) ────────────────────────
Route::post('/api/mpesa/callback', [MpesaCallbackController::class, 'callback'])
    ->name('mpesa.callback');