<?php

namespace App\Repositories\Implementations;

use App\Models\FamilyAccount;
use App\Repositories\Interfaces\FamilyAccountRepositoryInterface;

class FamilyAccountRepository implements FamilyAccountRepositoryInterface
{
    public function all(): iterable
    {
        return FamilyAccount::all();
    }

    public function find($id): ?object
    {
        return FamilyAccount::find($id);
    }

    public function create(array $data): object
    {
        return FamilyAccount::create($data);
    }

    public function update($id, array $data): bool
    {
        $familyAccount = FamilyAccount::find($id);
        if (!$familyAccount) {
            return false;
        }
        return $familyAccount->update($data);
    }

    public function delete($id): bool
    {
        return FamilyAccount::destroy($id);
    }
}
