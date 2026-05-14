<?php

namespace App\Repositories\Implementations;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function all(): iterable
    {
        return Role::all();
    }

    public function find($id): ?object
    {
        return Role::find($id);
    }

    public function create(array $data): object
    {
        return Role::create($data);
    }

    public function update($id, array $data): bool
    {
        $role = Role::find($id);
        if (!$role) {
            return false;
        }
        return $role->update($data);
    }

    public function delete($id): bool
    {
        return Role::destroy($id);
    }

    public function findByTitle(string $title): ?Role
    {
        return Role::where('title', $title)->first();
    }
}
