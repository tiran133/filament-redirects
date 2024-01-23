<?php

namespace Codedor\FilamentRedirects\Http\Middleware;

use Closure;
use Codedor\FilamentRedirects\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Redirects
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        $urlMaps = Cache::rememberForever('redirects', function () {
            return Redirect::orderBy('sort_order')
                ->where('online', 1)
                ->get();
        });

        // Decode urls
        $uri = urldecode($request->getUri());
        $requestUri = urldecode($request->getRequestUri());

        // Convert to ascii (for special characters)
        $uri = Str::ascii($uri);
        $requestUri = Str::ascii($requestUri);

        $current = [
            'full' => $uri,
            'fullNoQuery' => Str::beforeLast($uri, '?'),
            'fullWithTrailingSlash' => Str::finish($uri, '/'),
            'fullWithoutTrailingSlash' => Str::replaceEnd($uri, '/'),
            'path' => $requestUri,
            'pathNoQuery' => Str::beforeLast($requestUri, '?'),
        ];

        $activeRedirect = $urlMaps->first(function ($redirect) use ($current) {
            $from = $redirect->clean_from;

            $hasWildcard = Str::contains($from, config('filament-redirects.route-wildcard', '*'));

            return
                ($hasWildcard && Str::is($from, $current['path'])) ||
                ($hasWildcard && Str::is($from, $current['full'])) ||
                (in_array($from, $current));
        });

        if (! $activeRedirect || $activeRedirect->clean_from === $activeRedirect->to) {
            return $next($request);
        }

        if ((int) $activeRedirect->status === 410) {
            return abort(410);
        }

        if ($activeRedirect->pass_query_string && $request->getQueryString()) {
            $to = $activeRedirect->to . '?' . $request->getQueryString();
        } else {
            $to = $activeRedirect->to;
        }

        return redirect($to, $activeRedirect->status);
    }
}
