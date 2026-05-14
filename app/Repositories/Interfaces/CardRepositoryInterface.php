<?php

namespace App\Repositories\Interfaces;

interface CardRepositoryInterface
{
    public function all(): iterable;
    public function allDefault(): iterable;
    public function allCustom(): iterable;
    public function find($id): ?object;
    public function create(array $data): object;
    public function update($id, array $data): bool;
    public function delete($id): bool;
    public function duplicateAsCustom($id): object;
}
