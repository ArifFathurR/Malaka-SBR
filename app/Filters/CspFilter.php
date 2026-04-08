<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CspFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null) {}

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader(
            'Content-Security-Policy',
            "default-src 'self';
             script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com;
             style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com;
             img-src 'self' data: https://*.tile.openstreetmap.org;
             connect-src 'self' https://*.tile.openstreetmap.org;"
        );
    }
}
