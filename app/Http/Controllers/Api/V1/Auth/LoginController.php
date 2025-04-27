<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoginController extends Controller
{


    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    /**
     * Login a user and return a token.
     * 
     * @param LoginRequest $request 
     * @return JsonResponse
     */
    public function store(LoginRequest $request): JsonResponse
    {

        try {
            $loginData = $this->authService->login($request->validated());

            return response()->json([
                'message' => 'Login successfully.',
                'token' => $loginData['token'],
                'user' => new UserResource($loginData['user']),
            ]);

        } catch (AuthenticationException $e) {
            return response()->json(['message' => 'Sorry your credentials are incorrect. Please try it again.'], 401);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed'], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
            
        }

    }


    /**
     * Logout the authenticated user.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
