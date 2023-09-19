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

        $current = [
            'full' => $request->getUri(),
            'fullNoQuery' => Str::beforeLast($request->getUri(), '?'),
            'path' => $request->getRequestUri(),
            'pathNoQuery' => Str::beforeLast($request->getRequestUri(), '?'),
        ];

        $activeRedirect = $urlMaps->first(function ($redirect) use ($current) {
            $hasWildcard = Str::contains(
                $redirect->clean_from,
                config('url-mapping.route-wildcard', '*')
            );

            return
                ($hasWildcard && Str::is($redirect->clean_from, $current['path'])) ||
                ($hasWildcard && Str::is($redirect->clean_from, $current['full'])) ||
                (in_array($redirect->clean_from, $current));
        });

        if (! $activeRedirect) {
            return $next($request);
        }

        if ((int) $activeRedirect->status === 410) {
            return abort(410);
        }

        if ($activeRedirect->pass_query_string) {
            $to = $activeRedirect->to . '?' . Str::afterLast($request->getUri(), '?');
        } else {
            $to = $activeRedirect->to;
        }

        return redirect($to, $activeRedirect->status);
    }
}
