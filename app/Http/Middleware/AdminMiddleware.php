<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        //管理者権限確認
        if (!auth()->check() || !auth()->user()->admin_flag) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}