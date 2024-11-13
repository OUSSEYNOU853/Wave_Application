<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth; // Gardons quand même l'import de Auth
use App\Interfaces\UserServiceInterface;
use App\Notifications\TransactionNotification;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    // Fonction pour enregistrer un utilisateur
    public function register(Request $request): JsonResponse
    {
        // La méthode register reste inchangée car elle est publique
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'secret_code' => 'required|integer|digits:4',
            'photo' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cni' => 'required|integer|unique:users,cni|digits_between:10,20',
            'role' => 'required|string|in:users,client,distributeur,marchand',
        ]);

        $user = $this->userService->register($data);

        if ($user instanceof User) {
            $user->notify(new TransactionNotification('Bienvenue ! Votre compte a été créé avec succès.'));
            return response()->json(['user' => $user, 'status' => 'registered', 'message' => 'Bienvenue ! Votre compte a été créé avec succès.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User creation failed.'], 500);
        }
    }

    // Fonction pour récupérer un utilisateur par son numéro de téléphone
    public function findByPhone(Request $request, $phone): JsonResponse
    {
        $user = Auth::user();

        if ($user->phone !== $phone) {
            return response()->json(['status' => 'error', 'message' => 'Vous ne pouvez pas accéder aux données d\'un autre utilisateur.'], 403);
        }

        $user = $this->userService->findByPhone($phone);
        return response()->json(['user' => $user]);
    }

    public function updateProfile(Request $request, $userId): JsonResponse
    {
        $user = Auth::user();

        if ($user->id !== (int) $userId) {
            return response()->json(['status' => 'error', 'message' => 'Vous ne pouvez pas modifier les données d\'un autre utilisateur.'], 403);
        }

        $data = $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'phone' => 'nullable|string|unique:users,phone,' . $userId,
            'email' => 'nullable|string|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8',
            'secret_code' => 'nullable|integer|digits:4',
            'photo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cni' => 'nullable|integer|unique:users,cni,' . $userId . '|digits_between:10,20',
            'role' => 'nullable|string|in:users,client',
        ]);

        $user = $this->userService->updateProfile($userId, $data);

        if ($user instanceof User) {
            $user->notify(new TransactionNotification("Vos données ont été modifiées."));
            return response()->json(['user' => $user, 'status' => 'updated', 'message' => "Vos données ont été modifiées."]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'La mise à jour a échoué.'], 500);
        }
    }
}