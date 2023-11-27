<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

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




Route::middleware(['auth'])->group(function () {

   Route::get('/dashboard', [TransactionController::class, 'dashboard'])->name('dashboard');

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions');
        Route::post('/deposit',  [TransactionController::class, 'doCashDeposit'])->name('deposit');
        Route::post('/withdraw',  [TransactionController::class, 'doCashWithdrawal'])->name('withdraw');
        Route::post('/transfer',  [TransactionController::class, 'doCashTransfer'])->name('transfer');
    });

});


require __DIR__.'/auth.php';
