<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Import')
                ->color('violet')
                ->label('Import Product')
                ->icon('heroicon-m-arrow-up-tray')
                ->form([
                    FileUpload::make('product_import_file')
                        ->label('Product Import File')
                        ->helperText(new HtmlString('Download the import template <a href="' . route('products.export-product-template') . '" style="font-weight: bold; color: #8b5cf6;">here</a>'))
                        ->required()
                ]),
            Actions\CreateAction::make()
                ->label('New Product')
                ->icon('heroicon-m-plus'),
        ];
    }
}
