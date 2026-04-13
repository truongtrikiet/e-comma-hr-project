<?php

use App\Http\Controllers\Admin\EditorImageUploadController;
use App\Http\Controllers\Image\ImageController;
use Illuminate\Support\Facades\Route;

Route::post('/editor-uploads', EditorImageUploadController::class)->name('admin.editor_upload');
Route::post('/editor-uploads-export', [EditorImageUploadController::class, 'exportFile'])->name('admin.editor_upload.export');

Route::get('image', ImageController::class)->name('admin.load_image');
