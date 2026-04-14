<?php

use App\Http\Controllers\Hr\ContractTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('contract-type', ContractTypeController::class)->names('hr.contract_type');
