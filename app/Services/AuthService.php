<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;


class AuthService
{


    /**
     * Log in a user.
     *
     * @param array $formData credentials
     * @return array
     */
    public function login(array $formData): array
    {

        $user = User::where('email', $formData['email'])->first();

        if (!$user || !Hash::check($formData['password'], $user->password)) {
            throw new AuthenticationException('Unauthorized User');
        }
    
        $token = $user->createToken($user->email)->plainTextToken;
    
        return [
            'token' => $token,
            'user' => $user 
        ];
    }

    /**
     * Log out the current user.
     * @param User $user 
     * @return void
     * 
     */
    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }

}