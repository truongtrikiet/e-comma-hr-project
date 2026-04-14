<?php

use App\Http\Controllers\Hr\ContractAttributeController;
use Illuminate\Support\Facades\Route;

Route::resource('contract-attribute', ContractAttributeController::class)->names('hr.contract_attribute');