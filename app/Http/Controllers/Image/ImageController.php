<?php

namespace App\Http\Controllers\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function __invoke(Request $request)
    {
        ob_end_clean();

        $fileUrl = $request->query('file_url');

        if (! $fileUrl) {
            abort(404);
        }

        return Response::make(Storage::get($fileUrl))
            ->header('Content-Type', Storage::mimeType($fileUrl));
    }
}
