<?php

namespace App\Repositories\Interfaces;

interface CardSetRepositoryInterface
{
    public function all(): iterable;
    public function find($id): ?object;
    public function create(array $data): object;
    public function update($id, array $data): bool;
    public function delete($id): bool;
    public function addCard($cardSetId, $cardId): void;
    public function removeCard($cardSetId, $cardId): void;
    public function assignToChild($cardSetId, $childId): void;
    public function detachFromChild($cardSetId, $childId): void;
}
