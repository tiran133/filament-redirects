<?php

use Codedor\FilamentRedirects\Models\Redirect;

it('prepends a slash', function () {
    $redirect = Redirect::factory()->create([
        'from' => 'test',
    ]);

    expect($redirect->clean_from)->toBe('/test');
});
