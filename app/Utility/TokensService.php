<?php

namespace App\Utility;

use App\Models\RefreshToken;
use App\Repositories\Implementations\RefreshTokenRepository;
use Illuminate\Support\Str;

class TokensService
{
    private RefreshTokenRepository $refreshTokenRepository;
    public function __construct(RefreshTokenRepository $refreshTokenRepository)
    {
        $this->refreshTokenRepository = $refreshTokenRepository;
    }
    public function generateRefreshToken(string $userId, int $ttlDays = 30): RefreshToken
    {
        return $this->refreshTokenRepository->create([
            'id' => Str::uuid()->toString(),
            'token' => Str::uuid()->toString(),
            'user_id' => $userId,
            'expires_at' => now()->addDays($ttlDays)
        ]);
    }
}
