<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPanelAccess
{
    /** Текст предупреждения на форме входа (см. resources/views/vendor/platform/auth/login.blade.php). */
    public const ACCESS_DENIED_MESSAGE = 'Вход в административную панель разрешён только глобальному администратору и администратору центра. Ваша роль не подходит — войдите под другой учётной записью.';

    private const ALLOWED_ACCESS_ROLE_TITLES = [
        'global_admin',
        'center_admin',
    ];

    /**
     * Разрешает админку только ролям из ТЗ (глобальный / админ центра).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return $next($request);
        }

        $title = $user->accessRole?->title;

        if (! in_array($title, self::ALLOWED_ACCESS_ROLE_TITLES, true)) {
            $guard = Auth::guard(config('platform.guard', 'web'));
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $lockCookieName = sprintf('%s_%s', $guard->getName(), '_orchid_lock');
            $forgetLockCookie = app(CookieJar::class)->forget($lockCookieName);

            return redirect()
                ->route('platform.login', ['admin_denied' => '1'])
                ->withCookie($forgetLockCookie);
        }

        return $next($request);
    }
}
