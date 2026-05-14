<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Dashboard');
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return __('Administration home: shortcuts to sections and summry information will appear here.');
    }

    /**
     * The screen's action buttons.
     *
     * @return iterable<int, \Orchid\Screen\Action>
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
