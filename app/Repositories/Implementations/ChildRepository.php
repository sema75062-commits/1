<?php

namespace App\Repositories\Implementations;

use App\Models\Child;
use App\Repositories\Interfaces\ChildRepositoryInterface;

class ChildRepository implements ChildRepositoryInterface
{
    public function all(): iterable
    {
        return Child::all();
    }

    public function find($id): ?object
    {
        return Child::find($id);
    }

    public function create(array $data): object
    {
        return Child::create($data);
    }

    public function update($id, array $data): bool
    {
        $child = Child::find($id);
        if (!$child) {
            return false;
        }
        return $child->update($data);
    }

    public function delete($id): bool
    {
        return Child::destroy($id);
    }
}
