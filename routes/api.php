<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected routes (require token)
Route::middleware('auth:sanctum')->group(function () {
    // Current user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Groups
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::post('/groups/join', [GroupController::class, 'joinGroup']);
    Route::get('/groups/{id}', [GroupController::class, 'apiShow']);

    Route::post('/groups/create', [GroupController::class, 'createGroup']);
    Route::get('/user/groups', [GroupController::class, 'userGroups']);
   

  


    // Contributions
    Route::post('/groups/{id}/contribute', [ContributionController::class, 'store']);

    // Withdrawals
    Route::post('/groups/{id}/withdraw', [WithdrawalRequestController::class, 'store']);
    Route::post('/withdrawals/{id}/approve', [WithdrawalRequestController::class, 'approve']);

    Route::get('/groups/{id}/transactions', [TransactionController::class, 'index']);
    Route::get('/user/transactions', [TransactionController::class, 'userTransactions']);
});

