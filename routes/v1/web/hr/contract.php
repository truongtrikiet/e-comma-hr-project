<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\ContractController;

Route::resource('contract', ContractController::class)->names('hr.contract');
Route::get('detail-contract/{contract}', [ContractController::class, 'showDetailPdf'])->name('hr.contract.contract-detail');
