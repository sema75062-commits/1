<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Card;

use App\Orchid\Layouts\Card\CardListLayout;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Orchid\Screen\Screen;

class CardListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'cards' => collect(),
        ];
    }

    public function name(): ?string
    {
        return __('Cards');
    }

    public function description(): ?string
    {
        return __('Teaching cards: title, illustration, and audio materials.');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.domains.cards',
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            CardListLayout::class,
        ];
    }
}
