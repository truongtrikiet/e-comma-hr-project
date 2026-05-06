<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\ContractController;

Route::resource('contract', ContractController::class)->names('staff.contract');
Route::get('detail-contract/{contract}/pdf', [ContractController::class, 'showDetailPdf'])->name('staff.contract.contract-detail');
