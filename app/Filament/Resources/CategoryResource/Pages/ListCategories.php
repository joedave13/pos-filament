<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Export')
                ->url(route('categories.export'))
                ->color('info')
                ->label('Export Category')
                ->icon('heroicon-m-arrow-down-tray'),
            Actions\CreateAction::make()
                ->label('New Category')
                ->icon('heroicon-m-plus'),
        ];
    }
}
