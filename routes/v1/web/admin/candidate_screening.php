<?php

use App\Http\Controllers\Admin\CandidateScreeningController;
use Illuminate\Support\Facades\Route;

Route::resource('candidate-screening', CandidateScreeningController::class)->names('admin.candidate-screening');
Route::post('candidate-screening/scan', [CandidateScreeningController::class, 'scan'])->name('admin.candidate-screening.scan');
Route::post('candidate-screening/delete-by-status', [CandidateScreeningController::class, 'deleteAllByStatus'])
	->name('admin.candidate-screening.delete-by-status');
Route::post('candidate-screening/{candidateScreening}/send-result-email', [CandidateScreeningController::class, 'sendResultEmail'])
	->name('admin.candidate-screening.send-result-email');
