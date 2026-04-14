<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\AppendixContractController;

Route::get('appendix-contract/create/{contract}', [AppendixContractController::class, 'create'])->name('hr.appendix_contract.create');
Route::post('appendix-contract/store/{contract}', [AppendixContractController::class, 'store'])->name('hr.appendix_contract.store');
Route::get('appendix-contract/edit/{contract}/{appendixContract}', [AppendixContractController::class, 'edit'])->name('hr.appendix_contract.edit');
Route::put('appendix-contract/update/{contract}/{appendixContract}', [AppendixContractController::class, 'update'])->name('hr.appendix_contract.update');
