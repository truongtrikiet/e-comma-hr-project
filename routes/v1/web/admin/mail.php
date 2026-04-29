<?php

use App\Http\Controllers\Admin\MailController;
use Illuminate\Support\Facades\Route;

Route::get('/mail', [MailController::class, 'index'])->name('admin.mail.index');
Route::post('/mail/send', [MailController::class, 'sendMail'])->name('admin.mail.send');
