<?php

namespace App\Services;

class EditorImageUploadService
{
    /**
     * Handle the getting file absolute path.
     */
    public function getFileAbsolutePath(string $fileUrl): string
    {
        $parsedUrl = parse_url($fileUrl);

        $decodedPath = urldecode($parsedUrl['query']);

        $relativePath = ltrim($decodedPath, 'file_url=public/media/');  

        $absolutePath = config('app.url') . '/storage/media/' . $relativePath;

        return $absolutePath;
    }
}
