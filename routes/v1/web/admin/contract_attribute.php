<?php

use App\Http\Controllers\Admin\ContractAttributeController;
use Illuminate\Support\Facades\Route;

Route::resource('contract-attribute', ContractAttributeController::class)->names('admin.contract_attribute');