<?php

namespace App\Repositories\Implementations;

use App\Models\CardSet;
use App\Repositories\Interfaces\CardSetRepositoryInterface;

class CardSetRepository implements CardSetRepositoryInterface
{
    public function all(): iterable
    {
        return CardSet::all();
    }

    public function find($id): ?object
    {
        return CardSet::find($id);
    }

    public function create(array $data): object
    {
        return CardSet::create($data);
    }

    public function update($id, array $data): bool
    {
        $cardSet = CardSet::find($id);
        if (!$cardSet) {
            return false;
        }
        return $cardSet->update($data);
    }

    public function delete($id): bool
    {
        return (bool) CardSet::destroy($id);
    }

    public function addCard($cardSetId, $cardId): void
    {
        $cardSet = CardSet::findOrFail($cardSetId);
        $cardSet->cards()->syncWithoutDetaching([$cardId]);
    }

    public function removeCard($cardSetId, $cardId): void
    {
        $cardSet = CardSet::findOrFail($cardSetId);
        $cardSet->cards()->detach($cardId);
    }

    public function assignToChild($cardSetId, $childId): void
    {
        $cardSet = CardSet::findOrFail($cardSetId);
        $cardSet->children()->syncWithoutDetaching([$childId]);
    }

    public function detachFromChild($cardSetId, $childId): void
    {
        $cardSet = CardSet::findOrFail($cardSetId);
        $cardSet->children()->detach($childId);
    }
}
