<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\ContractController;

Route::resource('contract', ContractController::class)->names('contract');
Route::get('detail-contract/{contract}', [ContractController::class, 'showDetail'])->name('contract-detail');
