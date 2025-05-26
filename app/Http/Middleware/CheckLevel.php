<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevel
{
    public function handle(Request $request, Closure $next, ...$levels): Response
    {
        if (!auth()->check()) {
            abort(403, 'Not authenticated');
        }

        $userLevel = strtolower(auth()->user()->level->level_code ?? '');

        $allowedLevels = array_map('strtolower', $levels);

        if (!in_array($userLevel, $allowedLevels)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
