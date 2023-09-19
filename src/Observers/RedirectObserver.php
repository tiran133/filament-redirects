<?php

namespace Codedor\FilamentRedirects\Observers;

use Codedor\FilamentRedirects\Models\Redirect;
use Illuminate\Support\Facades\Cache;

class RedirectObserver
{
    public function created(Redirect $domain)
    {
        Cache::forget('redirects');
    }

    public function updated(Redirect $domain)
    {
        Cache::forget('redirects');
    }

    public function deleted(Redirect $domain)
    {
        Cache::forget('redirects');
    }
}
