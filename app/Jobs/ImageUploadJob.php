<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\LocalStorageService;
use App\Services\CloudinaryStorageService;

class ImageUploadJob
{
    protected $user;
    protected $imageFile;
    protected $cloudStorageService;
    protected $localStorageService;

    public function __construct(
        User $user,
        string $imageFile,
        CloudinaryStorageService $cloudStorageService,
        LocalStorageService $localStorageService
    ) {
        $this->user = $user;
        $this->imageFile = $imageFile;
        $this->cloudStorageService = $cloudStorageService;
        $this->localStorageService = $localStorageService;
    }

    public function handle()
    {
        $imagePath = 'users/' . time() . '.jpg';

        try {
            // Try uploading to Cloudinary first
            $imageUrl = $this->cloudStorageService->upload($this->imageFile, $imagePath);
            $this->user->photo = $imageUrl;
            $this->user->save();
        } catch (\Exception $e) {
            // Fallback to local storage if Cloudinary upload fails
            $imageUrl = $this->localStorageService->upload($this->imageFile, $imagePath);
            $this->user->photo = $imageUrl;
            $this->user->save();

            // Optionally retry uploading to Cloudinary after a delay
            $this->retryCloudinaryUpload($imagePath);
        }
    }

    protected function retryCloudinaryUpload(string $imagePath)
    {
        // Use a background job to retry the Cloudinary upload after 10 minutes
        \Illuminate\Support\Facades\Queue::laterOn('uploads', now()->addMinutes(10), new ImageUploadJob(
            $this->user,
            $this->imageFile,
            $this->cloudStorageService,
            $this->localStorageService
        ));
    }
}
