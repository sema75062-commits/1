<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Child;

use App\Orchid\Layouts\Child\ChildListLayout;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Orchid\Screen\Screen;

class ChildListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'children' => collect(),
        ];
    }

    public function name(): ?string
    {
        return __('Children');
    }

    public function description(): ?string
    {
        return __('Learners registered in the platform: personal data and links to centers and programs.');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.domains.children',
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            ChildListLayout::class,
        ];
    }
}
