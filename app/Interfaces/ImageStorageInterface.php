<?php

namespace App\Interfaces;

interface ImageStorageInterface
{
    public function upload(string $file, string $path): string;
    public function delete(string $path): bool;
}
