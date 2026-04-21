<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ResumeTextService
{
    public function __construct(
        //
    ) {
        //
    }

    public function extract(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        switch ($ext) {
            case 'txt':
                return file_get_contents($file->getRealPath());

            case 'pdf':
                return (string) shell_exec('pdftotext ' . escapeshellarg($file->getRealPath()) . ' -');

            case 'docx':
                $zip = new \ZipArchive();
                if ($zip->open($file->getRealPath()) === true) {
                    $index = $zip->locateName('word/document.xml');
                    if ($index !== false) {
                        $xml = $zip->getFromIndex($index);
                        $zip->close();

                        $xml = str_replace(['</w:p>', '</w:tbl>'], "\n", $xml);
                        $text = strip_tags($xml);
                        return preg_replace('/\s+/', ' ', trim(html_entity_decode($text)));
                    }
                    $zip->close();
                }
                return '';

            case 'doc':
                $out = @shell_exec('antiword ' . escapeshellarg($file->getRealPath()));
                return $out ? (string) $out : '';

            default:
                return '';
        }
    }
}
