<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AppendixContractController;

Route::get('appendix-contract/create/{contract}', [AppendixContractController::class, 'create'])->name('admin.appendix_contract.create');
Route::post('appendix-contract/store/{contract}', [AppendixContractController::class, 'store'])->name('admin.appendix_contract.store');
Route::get('appendix-contract/edit/{contract}/{appendixContract}', [AppendixContractController::class, 'edit'])->name('admin.appendix_contract.edit');
Route::put('appendix-contract/update/{contract}/{appendixContract}', [AppendixContractController::class, 'update'])->name('admin.appendix_contract.update');
