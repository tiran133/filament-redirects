<?php

namespace Codedor\FilamentRedirects\Filament\RedirectResource\Pages;

use Codedor\FilamentRedirects\Filament\RedirectResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRedirects extends ManageRecords
{
    protected static string $resource = RedirectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
