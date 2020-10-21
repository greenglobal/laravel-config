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
        if (! empty($routeName = $request->route()->getName())) {
            $throttle = app('GGPHP\Config\Helpers\Config')->getThrottle($routeName);
            $maxAttempts = (int) ($throttle['max_attempts'] ?? $maxAttempts);
            $decayMinutes = (int) ($throttle['decay_minutes'] ?? $decayMinutes);
        }

        if (is_string($maxAttempts)
            && func_num_args() === 3
            && ! is_null($limiter = $this->limiter->limiter($maxAttempts))) {
            return $this->handleRequestUsingNamedLimiter($request, $next, $maxAttempts, $limiter);
        }

        return $this->handleRequest(
            $request,
            $next,
            [
                (object) [
                    'key' => $prefix.$this->resolveRequestSignature($request),
                    'maxAttempts' => $this->resolveMaxAttempts($request, $maxAttempts),
                    'decayMinutes' => $decayMinutes,
                    'responseCallback' => null,
                ],
            ]
        );
    }
}
