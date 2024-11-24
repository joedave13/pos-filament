<?php

namespace App\Filament\Resources\ShopSettingResource\Pages;

use App\Filament\Resources\ShopSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShopSettings extends ManageRecords
{
    protected static string $resource = ShopSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Shop Setting')
                ->icon('heroicon-m-plus'),
        ];
    }
}
