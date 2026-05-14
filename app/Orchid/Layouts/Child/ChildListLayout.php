<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Child;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ChildListLayout extends Table
{
    public $target = 'children';

    public function columns(): array
    {
        return [];
    }
}
