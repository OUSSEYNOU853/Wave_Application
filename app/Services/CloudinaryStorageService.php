<?php

namespace App\Services;

use App\Interfaces\ImageStorageInterface;

class CloudinaryStorageService implements ImageStorageInterface
{
    protected $cloudName;
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name');
        $this->apiKey = config('services.cloudinary.api_key');
        $this->apiSecret = config('services.cloudinary.api_secret');
    }

    public function upload(string $file, string $path): string
    {
        // Construct the full path including the "wave" folder
        $publicId = 'wave/' . $path;  // 'wave' est le dossier dans lequel l'image sera stockée

        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/upload";

        // Préparer les données de la requête, inclure le fichier et le public_id dans le dossier "wave"
        $data = [
            'file' => new \CURLFile($file), // Le fichier à envoyer
            'public_id' => $publicId,       // Le chemin dans lequel l'image sera stockée (dossier "wave")
            'upload_preset' => 'your_upload_preset', // Si vous avez un upload preset configuré dans Cloudinary
        ];

        // Effectuer la requête HTTP pour télécharger l'image
        $response = $this->makeHttpRequest($url, 'POST', $data);

        // Vérifier si l'upload a réussi et retourner l'URL sécurisé de l'image
        if ($response['http_code'] === 200) {
            return $response['body']['secure_url']; // Retourner l'URL sécurisée
        } else {
            throw new \Exception("Failed to upload image to Cloudinary: " . $response['body']['error']['message']);
        }
    }

    public function delete(string $path): bool
    {
        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/delete_by_token";
        $data = [
            'token' => $path,
        ];

        $response = $this->makeHttpRequest($url, 'POST', $data);

        return $response['http_code'] === 200;
    }

    protected function makeHttpRequest(string $url, string $method, array $data): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiSecret);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'http_code' => $httpCode,
            'body' => json_decode($response, true),
        ];
    }
}
