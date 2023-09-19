<?php

use Codedor\FilamentRedirects\Filament\RedirectResource\Pages\ManageRedirects;
use Codedor\FilamentRedirects\Models\Redirect;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->redirects = Redirect::factory()->createMany([
        [
            'from' => '/one',
            'to' => '/two',
        ],
        [
            'from' => '/foo',
            'to' => '/bar',
        ],
    ]);

    $this->actingAs(\Codedor\FilamentRedirects\Tests\Fixtures\Models\User::factory()->create());
});

it('can list redirects', function () {
    livewire(ManageRedirects::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($this->redirects);
});

it('has an edit action', function () {
    livewire(ManageRedirects::class)
        ->assertTableActionExists('edit');
});

it('has a delete action', function () {
    livewire(ManageRedirects::class)
        ->assertTableActionExists('delete')
        ->assertTableBulkActionExists('delete');
});

it('has an import action that can throw an error', function () {
    livewire(ManageRedirects::class)
        ->assertActionExists('import')
        ->callAction('import');

    Notification::assertNotified('Something went wrong during the import');
});

it('has an import action that can truncate the table', function () {
    Storage::disk('local')->put(
        'import_redirects.xlsx',
        file_get_contents(__DIR__ . '/../../../../Fixtures/import_redirects.xlsx', 'import_redirects.xlsx')
    );

    livewire(ManageRedirects::class)
        ->assertActionExists('import')
        ->callAction('import', ['file' => ['file' => 'import_redirects.xlsx'],
        ]);

    Notification::assertNotified(
        Notification::make()
            ->success()
            ->title('Import was successful')
    );

    $this->assertDatabaseCount(Redirect::class, 3);
    $this->assertDatabaseHas(Redirect::class, [
        'from' => '/from',
        'to' => '/to',
        'status' => 301,
    ]);
});

it('can create a redirect with validation errors', function () {
    livewire(ManageRedirects::class)
        ->assertActionExists('create')
        ->callAction('create', [
            'from' => '/from',
        ])
        ->assertHasActionErrors(['to' => 'required']);
});

it('can create a redirect', function () {
    livewire(ManageRedirects::class)
        ->assertActionExists('create')
        ->callAction('create', [
            'from' => '/from',
            'to' => '/to',
            'status' => 410
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseCount(Redirect::class, 3);
    $this->assertDatabaseHas(Redirect::class, [
        'from' => '/from',
        'to' => '/to',
        'status' => 410,
    ]);
});
