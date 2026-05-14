<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Card;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CardListLayout extends Table
{
    public $target = 'cards';

    public function columns(): array
    {
        return [];
    }
}
