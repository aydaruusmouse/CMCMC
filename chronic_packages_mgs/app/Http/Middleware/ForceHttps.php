<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->secure() && $this->shouldForceHttps()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }

    private function shouldForceHttps(): bool
    {
        if (filter_var(env('APP_FORCE_HTTPS', false), FILTER_VALIDATE_BOOL)) {
            return true;
        }

        return str_starts_with((string) config('app.url'), 'https://');
    }
}
