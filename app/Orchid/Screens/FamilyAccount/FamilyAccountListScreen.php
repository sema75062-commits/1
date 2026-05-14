<?php

declare(strict_types=1);

namespace App\Orchid\Screens\FamilyAccount;

use App\Orchid\Layouts\FamilyAccount\FamilyAccountListLayout;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Orchid\Screen\Screen;

class FamilyAccountListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'family_accounts' => collect(),
        ];
    }

    public function name(): ?string
    {
        return __('Family accounts');
    }

    public function description(): ?string
    {
        return __('Family units linking parents and children to shared access and content.');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.domains.family_accounts',
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            FamilyAccountListLayout::class,
        ];
    }
}
