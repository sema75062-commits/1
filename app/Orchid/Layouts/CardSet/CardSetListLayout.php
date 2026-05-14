<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CardSet;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CardSetListLayout extends Table
{
    public $target = 'card_sets';

    public function columns(): array
    {
        return [];
    }
}
