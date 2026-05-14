<?php

namespace App\Repositories\Interfaces;

interface CenterRepositoryInterface
{
    public function all(): iterable;
    public function find(int $id): ?object;
    public function create(array $data): object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
