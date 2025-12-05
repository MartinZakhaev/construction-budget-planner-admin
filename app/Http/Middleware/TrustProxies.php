<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (recommended for Traefik / Dokploy / Docker setups)
     *
     * If you want to restrict it later, you can put an IP array here instead.
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * Headers used to detect proxy forwarded values (FULL MODE)
     *
     * These ensure Laravel correctly detects HTTPS, host, port, scheme.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
