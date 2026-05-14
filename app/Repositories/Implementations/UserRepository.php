<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all(): iterable
    {
        return User::all();
    }

    public function find($id): ?object
    {
        return User::find($id);
    }

    public function create(array $data): object
    {
        return User::create($data);
    }

    public function update($id, array $data): bool
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }
        return $user->update($data);
    }

    public function delete($id): bool
    {
        return User::destroy($id);
    }
}
