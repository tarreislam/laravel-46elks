<?php

namespace Tarre\Laravel46Elks\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Validate46ElksOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $ip = $request->getClientIp();

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $grantAccess = $this->allowOrigin($ip, 'ipv4');
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $grantAccess = $this->allowOrigin($ip, 'ipv6');
        } else {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid client IP address');
        }

        if (!$grantAccess) {
            return abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);

    }

    /**
     * @param $ip
     * @param $source
     * @return bool
     */
    protected function allowOrigin($ip, $source)
    {
        $originsToCheck = config("laravel-46elks.{$source}_origins", null);

        if ($originsToCheck === false) {
            return true;
        }

        $originsArr = explode('|', $originsToCheck);

        return in_array($ip, $originsArr);
    }
}
