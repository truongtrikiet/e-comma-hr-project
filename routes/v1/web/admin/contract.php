<?php

use App\Http\Controllers\Admin\ContractController;
use Illuminate\Support\Facades\Route;

Route::resource('contract', ContractController::class)->names('admin.contract');
Route::get('detail-contract/{contract}/pdf', [ContractController::class, 'showDetailPdf'])->name('admin.contract.contract-detail');
