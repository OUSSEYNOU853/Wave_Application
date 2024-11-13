<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Services\CloudinaryStorageService;
use App\Services\LocalStorageService;
use App\Jobs\ImageUploadJob;

class UploadUserProfileImage
{
    protected $cloudStorageService;
    protected $localStorageService;

    public function __construct(
        CloudinaryStorageService $cloudStorageService,
        LocalStorageService $localStorageService
    ) {
        $this->cloudStorageService = $cloudStorageService;
        $this->localStorageService = $localStorageService;
    }

    public function handle(UserCreated $event)
    {
        $user = $event->user;
        $imageFile = $event->profileImage; 
        if ($imageFile) {
            // Dispatch the job to upload the image
            dispatch(new ImageUploadJob(
                $user,
                $imageFile,
                $this->cloudStorageService,
                $this->localStorageService
            ));
        }
    }
}
