<?php

namespace GGPHP\Config\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests as CoreThrottleRequests;

class ThrottleRequests extends CoreThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  float|int  $decayMinutes
     * @param  string  $prefix
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        $routeName = ! empty($routeName = $request->route()->getName())
            ? $routeName : str_replace(['-', '/'], '_', $request->route()->uri());
        $throttle = getThrottle($routeName);

        if (! empty($throttle)) {
            $maxAttempts = (int) ($throttle['max_attempts'] ?? $maxAttempts);
            $decayMinutes = (int) ($throttle['decay_minutes'] ?? $decayMinutes);
        }

        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }
}
