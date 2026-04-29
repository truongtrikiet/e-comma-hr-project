<?php

use App\Http\Controllers\Hr\MailController;
use Illuminate\Support\Facades\Route;

Route::get('/mail', [MailController::class, 'index'])->name('hr.mail.index');
Route::post('/mail/send', [MailController::class, 'sendMail'])->name('hr.mail.send');
