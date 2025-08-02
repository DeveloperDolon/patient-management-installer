<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LicenceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $licensePath = base_path('../license.exe');

        if (!file_exists($licensePath)) {
            abort(403, 'License file missing.');
        }

        $licenseBinary = explode(' ', trim(file_get_contents($licensePath)));
        $user = get_current_user();

        $license = '';

        foreach ($licenseBinary as $binary) {
            $license .= chr(bindec($binary));
        }

        $secret = getenv('LICENSE_SECRET_KEY');

        $licenseHash = hash('sha256', $secret . $license);
        $expectedHash = hash('sha256', $secret . $user);

        if ($licenseHash !== $expectedHash) {
            abort(403, 'Invalid license.');
        }

        return $next($request);
    }

}
