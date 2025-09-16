<?php

use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pos/ticket/{saleId}', [PosController::class, 'generateTicket'])->name('pos.ticket');
Route::get('/pos/receipt/{saleId}', [PosController::class, 'generateReceipt'])->name('pos.receipt');