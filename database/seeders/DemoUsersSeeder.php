<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role as OrchidRole;

/**
 * Тестовые пользователи под роли из RoleSeeder.
 * Пароль у всех: 123.
 * Orchid-права: через роли из DomainOrchidRolesSeeder (для админов центра/глобала).
 */
class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUser(
            'Глобальный админ (демо)',
            'global-admin@demo.local',
            'global_admin',
            DomainOrchidRolesSeeder::SLUG_GLOBAL_ADMIN,
        );

        $this->seedUser(
            'Админ центра (демо)',
            'center-admin@demo.local',
            'center_admin',
            DomainOrchidRolesSeeder::SLUG_CENTER_ADMIN,
        );

        $this->seedUser(
            'Педагог (демо)',
            'teacher@demo.local',
            'teacher',
            null,
        );

        $this->seedUser(
            'Родитель (демо)',
            'parent@demo.local',
            'user',
            null,
        );
    }

    private function seedUser(
        string $name,
        string $email,
        string $accessRoleTitle,
        ?string $orchidRoleSlug,
    ): void {
        $roleId = Role::query()->where('title', $accessRoleTitle)->value('id');

        if ($roleId === null) {
            $this->command?->warn("Пропуск {$email}: нет access_roles.title = {$accessRoleTitle}");

            return;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => '123',
                'role_id' => $roleId,
                'permissions' => null,
                'email_verified_at' => now(),
            ],
        );

        if ($orchidRoleSlug !== null) {
            $orchidRole = OrchidRole::query()->where('slug', $orchidRoleSlug)->first();
            if ($orchidRole === null) {
                $this->command?->warn("Пропуск Orchid-роли для {$email}: нет slug = {$orchidRoleSlug}");
                $user->replaceRoles([]);
            } else {
                $user->replaceRoles([$orchidRole->id]);
            }

            return;
        }

        $user->replaceRoles([]);
    }
}
