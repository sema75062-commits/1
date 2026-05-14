<?php

namespace App\Repositories\Implementations;

use App\Models\Center;
use App\Repositories\Interfaces\CenterRepositoryInterface;

class CenterRepository implements CenterRepositoryInterface
{
    public function all(): iterable
    {
        return Center::all();
    }

    public function find($id): ?object
    {
        return Center::find($id);
    }

    public function create(array $data): object
    {
        return Center::create($data);
    }

    public function update($id, array $data): bool
    {
        $center = Center::find($id);
        if (!$center) {
            return false;
        }
        return $center->update($data);
    }

    public function delete($id): bool
    {
        return Center::destroy($id);
    }
}
