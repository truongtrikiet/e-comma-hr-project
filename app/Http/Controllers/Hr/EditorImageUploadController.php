<?php

namespace App\Http\Controllers\Hr;

use App\Enum\ImageSize;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\EditorImageUploadService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class EditorImageUploadController extends Controller
{
    const MAX_FILE_SIZE = 10485760;

    public function __construct(
        protected EditorImageUploadService $editorImageUploadService ,
    )
    {
        //
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            if ($file->getSize() > self::MAX_FILE_SIZE) {
                return response()->json([
                    'uploaded' => 0,
                    'error' => [
                        'message' => 'Kích thước tệp tin vượt quá giới hạn cho phép (10MB).'
                    ]
                ], 400);
            }

            $originName = $file->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $datePath = now()->format('Y/m/d');
            $path = $file->storeAs("public/media/{$datePath}", $fileName);

            $url = route('hr.load_image', ['file_url' => $path]);

            return response()->json([
                'fileName' => $fileName,
                'uploaded' => true,
                'url' => $url
            ]);
        }
    }

    /**
     * Handle the exproting file.
     */
    public function exportFile(Request $request) {
        $content = $request->input('content');

        $imageUrls = extractImageUrls($content);

        if (!empty($imageUrls)) {
            foreach ($imageUrls as $url) {
                $absolutePath = $this->editorImageUploadService->getFileAbsolutePath($url);
                $content = str_replace($url, $absolutePath, $content);
            }
        }
        return response()->json(['content' => $content]);
    }
}
