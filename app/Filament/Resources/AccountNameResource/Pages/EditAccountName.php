<?php

namespace App\Filament\Resources\AccountNameResource\Pages;

use App\Filament\Resources\AccountNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountName extends EditRecord
{
    protected static string $resource = AccountNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
