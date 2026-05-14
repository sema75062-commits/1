<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Center;

use App\Orchid\Layouts\Center\CenterListLayout;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Orchid\Screen\Screen;

class CenterListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'centers' => collect(),
        ];
    }

    public function name(): ?string
    {
        return __('Centers');
    }

    public function description(): ?string
    {
        return __('Centers in the system: contacts, address, and assignment of a center administrator.');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.domains.centers',
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            CenterListLayout::class,
        ];
    }
}
