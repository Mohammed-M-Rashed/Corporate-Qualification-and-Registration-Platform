<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function uploadPdf(UploadedFile $file, string $directory = 'documents'): array
    {
        // Validate PDF
        if ($file->getMimeType() !== 'application/pdf') {
            throw new \Exception('يجب أن يكون الملف من نوع PDF');
        }

        // Validate size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \Exception('حجم الملف يجب أن يكون أقل من 10 ميجابايت');
        }

        $path = $file->store($directory, 'public');

        return [
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ];
    }

    public function deleteFile(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }
}
