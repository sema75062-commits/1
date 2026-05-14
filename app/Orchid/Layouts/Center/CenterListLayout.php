<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Center;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CenterListLayout extends Table
{
    public $target = 'centers';

    public function columns(): array
    {
        return [];
    }
}
