<?php

use App\Http\Controllers\Hr\EditorImageUploadController;
use App\Http\Controllers\Image\ImageController;
use Illuminate\Support\Facades\Route;

Route::post('/editor-uploads', EditorImageUploadController::class)->name('hr.editor_upload');
Route::post('/editor-uploads-export', [EditorImageUploadController::class, 'exportFile'])->name('hr.editor_upload.export');

Route::get('image', ImageController::class)->name('hr.load_image');
