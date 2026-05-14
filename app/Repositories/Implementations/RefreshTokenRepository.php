<?php

namespace App\Repositories\Implementations;

use App\Models\RefreshToken;
use App\Repositories\Interfaces\RefreshTokenRepositoryInterface;
use Illuminate\Support\Str;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function all(): iterable
    {
        return RefreshToken::all();
    }

    public function find($id): ?object
    {
        return RefreshToken::find($id);
    }

    public function create(array $data): object
    {
        return RefreshToken::create($data);
    }

    public function update($id, array $data): bool
    {
        $refreshToken = RefreshToken::find($id);
        if (!$refreshToken) {
            return false;
        }
        return $refreshToken->update($data);
    }

    public function delete($id): bool
    {
        return RefreshToken::destroy($id);
    }

    public function getActiveToken($userId): ?RefreshToken
    {
        return RefreshToken::where('user_id', $userId)->where('revoked_at', null)->first();
    }

    public function revokeToken($id)
    {
        $this->update($id, ['revoked_at' => now()]);
    }

    public function revokeAllUserTokens($userId)
    {
        $activeTokens = RefreshToken::where('user_id', $userId)->where('revoked_at', null)->get();
        foreach ($activeTokens as $activeToken) {
            $this->update($activeToken->getKey(), ['revoked_at' => now()]);
        }
    }

    public function findByToken(string $token): ?RefreshToken
    {
        return RefreshToken::where('token', $token)->first();
    }
}
