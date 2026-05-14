<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\FamilyAccount;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FamilyAccountListLayout extends Table
{
    public $target = 'family_accounts';

    public function columns(): array
    {
        return [];
    }
}
