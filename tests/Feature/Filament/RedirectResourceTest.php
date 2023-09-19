<?php

use Codedor\FilamentRedirects\Filament\RedirectResource;
use Codedor\FilamentRedirects\Models\Redirect;
use Codedor\FilamentRedirects\Tests\Fixtures\Models\User;

beforeEach(function () {
    Redirect::factory()->create();

    $this->actingAs(User::factory()->create());
});

it('has an index page', function () {
    $this->get(RedirectResource::getUrl('index'))->assertSuccessful();
});

it('has only an index and edit action', function () {
    expect(RedirectResource::getPages())
        ->toHaveCount(1)
        ->toHaveKeys(['index']);
});
