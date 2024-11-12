<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning')
                ->label('Edit Product')
                ->icon('heroicon-m-pencil'),
            Actions\DeleteAction::make()
                ->label('Delete Product')
                ->icon('heroicon-m-trash'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make(3)
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('slug'),
                    TextEntry::make('category.name'),
                ]),
            TextEntry::make('description')->columnSpanFull(),
            Grid::make(3)
                ->schema([
                    TextEntry::make('stock')->numeric(),
                    TextEntry::make('price')->numeric(thousandsSeparator: '.')->prefix('Rp.'),
                    TextEntry::make('barcode')->default('-'),
                ]),
            ImageEntry::make('image')
                ->circular()
                ->size(130),
            IconEntry::make('is_active')
                ->label('Is Active?')
        ]);
    }
}
