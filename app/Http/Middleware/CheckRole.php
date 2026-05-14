<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /*
      проверяет, что у аутентифицированного пользователя есть одна из указанных ролей
      роли передаются через параметры middleware: role:global_admin, center_admin
    */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $userRole = $user->role?->title ?? null;

        if (!in_array($userRole, $roles, true)) {
            return response()->json(['message' => 'Forbidden. Insufficient role.'], 403);
        }

        return $next($request);
    }
}
