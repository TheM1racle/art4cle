<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {

        if (!$request->user()) {
            return redirect('/login');
        }


        $roleName = $request->user()->role?->name;

        if (!$roleName || !in_array($roleName, $roles)) {
            abort(403, 'Доступ в этот раздел запрещён для вашей роли');
        }


        return $next($request);
    }
}
