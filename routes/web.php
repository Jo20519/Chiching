<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great! 
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('index'); // Serve the Chiching home page
});

Route::get('/offline', function () {
    return view('offline');
});



Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/create-group', function () {
    return view('create-group');
});

Route::get('/join-group', function () {
    return view('join-group');
});

Route::get('/groups', function () {
    return view('groups');
});

Route::get('/groups/{id}', [GroupController::class, 'show']);

Route::get('/groups/{id}/transactions-page', [PageController::class, 'transactionsPage'])
     ->name('group.transactions.page');

     Route::get('/groups/{id}/transactions', function ($id) {
        return view('transactions', ['id' => $id]);
    })->name('transactions.page');
    