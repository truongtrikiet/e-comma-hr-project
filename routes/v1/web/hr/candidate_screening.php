<?php

use App\Http\Controllers\Hr\CandidateScreeningController;
use Illuminate\Support\Facades\Route;

Route::resource('candidate-screening', CandidateScreeningController::class)->names('hr.candidate-screening');
Route::post('candidate-screening/scan', [CandidateScreeningController::class, 'scan'])->name('hr.candidate-screening.scan');
Route::post('candidate-screening/delete-by-status', [CandidateScreeningController::class, 'deleteAllByStatus'])
	->name('hr.candidate-screening.delete-by-status');
