<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make(__('Dashboard'))
                ->icon('bs.house')
                ->route('platform.main'),

            Menu::make(__('Centers'))
                ->icon('bs.building')
                ->title(__('PECS'))
                ->route('platform.domains.centers')
                ->permission('platform.domains.centers'),

            Menu::make(__('Children'))
                ->icon('bs.person-lines-fill')
                ->route('platform.domains.children')
                ->permission('platform.domains.children'),

            Menu::make(__('Cards'))
                ->icon('bs.postcard')
                ->route('platform.domains.cards')
                ->permission('platform.domains.cards'),

            Menu::make(__('Card sets'))
                ->icon('bs.collection')
                ->route('platform.domains.card_sets')
                ->permission('platform.domains.card_sets'),

            Menu::make(__('Family accounts'))
                ->icon('bs.people')
                ->route('platform.domains.family_accounts')
                ->permission('platform.domains.family_accounts'),

            Menu::make(__('Teacher assignments'))
                ->icon('bs.person-badge')
                ->route('platform.domains.teacher_assignments')
                ->permission('platform.domains.teacher_assignments')
                ->divider(),

            Menu::make(__('Users'))
                ->icon('bs.person-gear')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('System')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('PECS'))
                ->addPermission('platform.domains.centers', __('Centers'))
                ->addPermission('platform.domains.children', __('Children'))
                ->addPermission('platform.domains.cards', __('Cards'))
                ->addPermission('platform.domains.card_sets', __('Card sets'))
                ->addPermission('platform.domains.family_accounts', __('Family accounts'))
                ->addPermission('platform.domains.teacher_assignments', __('Teacher assignments')),

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
