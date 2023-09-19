<?php

namespace Codedor\FilamentRedirects\Observers;

use Codedor\FilamentRedirects\Models\Redirect;
use Illuminate\Support\Facades\Cache;

class RedirectObserver
{
    public function created(Redirect $redirect)
    {
        Cache::forget('redirects');
    }

    public function updated(Redirect $redirect)
    {
        Cache::forget('redirects');
    }

    public function deleted(Redirect $redirect)
    {
        Cache::forget('redirects');
    }
}
