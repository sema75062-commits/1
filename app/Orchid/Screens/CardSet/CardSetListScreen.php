<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CardSet;

use App\Orchid\Layouts\CardSet\CardSetListLayout;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Orchid\Screen\Screen;

class CardSetListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'card_sets' => collect(),
        ];
    }

    public function name(): ?string
    {
        return __('Card sets');
    }

    public function description(): ?string
    {
        return __('Aggregations of cards used in lessons and child programs.');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.domains.card_sets',
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            CardSetListLayout::class,
        ];
    }
}
