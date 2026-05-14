<?php

namespace App\Http\Controllers;

use App\Repositories\Implementations\RefreshTokenRepository;
use App\Repositories\Implementations\RoleRepository;
use App\Repositories\Implementations\UserRepository;
use App\Utility\TokensService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;
    private TokensService $tokensService;
    private RefreshTokenRepository $refreshTokenRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, TokensService $tokensService, RefreshTokenRepository $refreshTokenRepository) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->tokensService = $tokensService;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function register(Request $request) {
        try {
            $credentials = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|max:255',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'role' => 'required'
            ]);

            $role = $this->roleRepository->findByTitle($credentials['role']);

            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures/users', 'public');
            }

            $user = $this->userRepository->create([
                'id' => Str::uuid(),
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
                'profile_picture_path' => $profilePicturePath,
                'role_id' => $role->getAttributeValue('id')
            ]);

            Log::info('A new user has registered successfully.', ['user_id' => $user->getAttributeValue('id'), 'email' => $credentials['email']]);

            return response()->json([
                'message' => 'Registered successfully.',
                'user_id' => $user->getAttributeValue('id'),
                'email' => $credentials['email']
            ], 201);
        } catch(\Exception $e) {
            $message = 'User registration failed: ' . $e->getMessage();
            echo $message;
            Log::error($message);
            return response()->json([
                'message' => $message,
            ], 401);
        }
    }

    public function login(Request $request) {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = Auth::user();
            $accessToken = $user->createToken('auth_token')->plainTextToken;

            $refreshToken = $this->tokensService->generateRefreshToken($user->getKey());

            return response()->json([
                'message' => 'Login successfully.',
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken->getAttributeValue('token'),
                'token_type' => 'Bearer'
            ]);
        } catch(\Exception $e) {
            $message = 'User authentication failed: ' . $e->getMessage();
            echo $message;
            Log::error($message);
            return response()->json([
                'message' => $message,
            ], 401);
        }
    }

    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();

            $refreshToken = $this->refreshTokenRepository->getActiveToken($request->user()->getKey());
            if ($refreshToken)
            {
                echo $refreshToken->getKey();
                $this->refreshTokenRepository->revokeToken($refreshToken->getKey());
            }

            Log::info('User logged out successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ]);
        } catch(\Exception $e) {
            $message = 'User logout failed: ' . $e->getMessage();
            echo $message;
            Log::error($message);
            return response()->json([
                'message' => $message,
            ], 401);
        }
    }

    public function refresh(Request $request) {
        try {
            $request->validate([
                'refresh_token' => 'required|string'
            ]);

            $refreshToken = $this->refreshTokenRepository->findByToken($request->input('refresh_token'));
            $revokedAt = $refreshToken->getAttributeValue('revoked_at');
            $expiresAt = Carbon::parse($refreshToken->getAttributeValue('expires_at'));

            if (!$refreshToken || $expiresAt->isPast())
            {
                return response()->json([
                    'error' => 'Refresh token expired, please login again'
                ], 401);
            }
            else if ($revokedAt !== null && Carbon::parse($revokedAt)->isPast())
            {
                $this->refreshTokenRepository->revokeAllUserTokens($request->user()->getKey());
                return response()->json([
                    'error' => 'Refresh token compromised, please login again'
                ], 401);
            }
            else
            {
                $this->refreshTokenRepository->revokeToken($refreshToken->getKey());
                $newRefreshToken = $this->tokensService->generateRefreshToken(request()->user()->getKey());
                $request->user()->currentAccessToken()->delete();
                $newAccessToken = $request->user()->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Token refresh successful.',
                    'access_token' => $newAccessToken,
                    'refresh_token' => $newRefreshToken->getAttributeValue('token'),
                    'token_type' => 'Bearer'
                ]);
            }
        } catch(\Exception $e) {
            $message = 'Refresh token failed: ' . $e->getMessage();
            echo $message;
            Log::error($message);
            return response()->json([
                'message' => $message,
            ], 401);
        }
    }
}
