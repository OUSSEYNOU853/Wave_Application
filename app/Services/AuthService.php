<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Events\AuthEvent;
use App\Facades\UserFacade;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function loginClient(array $credentials)
    {
        $user = $this->authRepository->findUserByCredentials($credentials);
        if (!$user) {
            return 'les donnees saisies sont incorrect verifie si vous avez belle et bien un compte sinon veillez creer un compte.';
        }

        $code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        Cache::put("sms_code_{$user->phone}", $code, 300);
        if (!Str::startsWith($user->phone, '+221')) {
            $user->phone = '+221' . $user->phone;
        }
        echo $code;
        event(new AuthEvent($user->phone, $code));
        return "Code envoyé par SMS vers {$user->phone}. Veuillez le vérifier pour continuer {$code}.";
    }

    public function verifySmsCode($phone, $code)
    {
        $cachedCode = Cache::get("sms_code_{$phone}");
        if ($cachedCode && $cachedCode == $code) {
            $user = UserFacade::findByPhone($phone);
            if ($user) {
                return $this->generateTokens($user);
            }
        }
        return 'Code incorrect ou expiré.';
    }

    public function loginDistributeur(array $credentials)
    {
        $user = $this->authRepository->findUserByCredentials($credentials);

        if (!$user) {
            return null;
        }

        // Check if it's a distributor's first login
        if ($user->role === RoleEnum::DISTRIBUTEUR->value && $user->is_first_login) {
            return [
                'message' => 'First login, please update your password and secret code.',
                'requires_update' => true,
            ];
        }

        // Generate tokens if the user has already updated their information
        return $this->generateTokens($user);
    }

    public function updateFirstLoginCredentials(array $data)
    {
        $user = $this->authRepository->findUserByEmail($data['email']);
        if (!$user) {
            return [
                'message' => 'Utilisateur non trouvé.',
                'success' => false,
            ];
        }

        if ($user->role !== RoleEnum::DISTRIBUTEUR->value || !$user->is_first_login) {
            return [
                'message' => 'Non autorisé. Cette action est uniquement permise lors de la première connexion.',
                'success' => false,
            ];
        }

        // Met à jour les informations de l'utilisateur
        $user->password = Hash::make($data['password']);
        $user->secret_code = Hash::make($data['secret_code']);
        $user->is_first_login = false;

        // Sauvegarde les changements
        $updated = $user->save();

        if ($updated) {
            $tokens = $this->generateTokens($user);

            return [
                'message' => 'Mot de passe et code secret mis à jour avec succès. Jetons générés.',
                'success' => true,
                'tokens' => $tokens,
            ];
        }

        return [
            'message' => 'Échec de la mise à jour des informations d\'identification. Veuillez réessayer.',
            'success' => false,
        ];
    }



    public function refresh(string $refreshToken)
    {
        $user = $this->authRepository->findUserByRefreshToken($refreshToken);
        if (!$user) {
            return null;
        }
        $this->authRepository->blacklistToken($refreshToken, 'refresh');
        $this->revokeTokens($user);
        return $this->generateTokens($user);
    }

    public function logout(string $token)
    {
        $this->authRepository->blacklistToken($token, 'access');
        Auth::logout();
        return true;
    }

    public function revokeTokens($user) {}

    public function generateTokens($user) {}
}
