<?php

namespace App\Http\Middleware;
use Closure;

class DebugBarMiddleware {

    public function handle($request, Closure $next) {
        if(\Auth::check() && \Auth::user()->id == 1) {
            config(['app.debug' => true]);
        }
        return $next($request);
    }
}
