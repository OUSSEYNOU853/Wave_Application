<?php

namespace App\Services;

use Cloudinary\Uploader;
use App\Interfaces\ImageUploadService;
use App\Interfaces\ImageStorageInterface;

class LocalStorageService implements ImageStorageInterface
{
    protected $storagePath;

    public function __construct()
    {
        $this->storagePath = config('services.local_storage.storage_path');
    }

    public function upload(string $file, string $path): string
    {
        $fullPath = $this->storagePath . '/' . $path;
        $directory = dirname($fullPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // create directories if they don't exist
        }

        move_uploaded_file($file, $fullPath); // move the file to the desired path
        return asset('storage/' . $path); // return the URL to access the image
    }

    public function delete(string $path): bool
    {
        $fullPath = $this->storagePath . '/' . $path;
        return unlink($fullPath);
    }
}
