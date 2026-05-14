<?php

namespace App\Repositories\Implementations;

use App\Models\Card;
use App\Repositories\Interfaces\CardRepositoryInterface;
use Illuminate\Support\Str;

class CardRepository implements CardRepositoryInterface
{
    public function all(): iterable
    {
        return Card::all();
    }

    public function allDefault(): iterable
    {
        return Card::where('is_default', true)->get();
    }

    public function allCustom(): iterable
    {
        return Card::where('is_default', false)->get();
    }

    public function find($id): ?object
    {
        return Card::find($id);
    }

    public function create(array $data): object
    {
        return Card::create($data);
    }

    public function update($id, array $data): bool
    {
        $card = Card::find($id);
        if (!$card) {
            return false;
        }

        return $card->update($data);
    }

    public function delete($id): bool
    {
        return (bool) Card::destroy($id);
    }
    
    // создаёт кастомную копию предустановленной карточки
    public function duplicateAsCustom($id): object
    {
        $original = Card::findOrFail($id);

        return Card::create([
            'id'           => Str::uuid()->toString(),
            'title'        => $original->title,
            'picture_path' => $original->picture_path,
            'audio_path'   => $original->audio_path,
            'is_default'   => false,
        ]);
    }
}
