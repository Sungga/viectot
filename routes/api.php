<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pages\PaymentController;

Route::post('/vnpay/ipn', [PaymentController::class, 'vnpay_ipn'])->name('vnpay.ipn');
