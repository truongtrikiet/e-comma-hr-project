<?php

use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Support\Facades\Route;

Route::resource('subject', SubjectController::class)->names('admin.subject');
