<?php

use App\Http\Controllers\Admin\ContractTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('contract-type', ContractTypeController::class)->names('admin.contract_type');
