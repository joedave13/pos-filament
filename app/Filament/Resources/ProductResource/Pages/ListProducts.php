<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Imports\ProductImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

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
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['product_import_file']);

                    try {
                        Excel::import(new ProductImport, $file);

                        Storage::disk('public')->delete($data['product_import_file']);

                        Notification::make()
                            ->title('Success')
                            ->body('Product imported successfully.')
                            ->success()
                            ->send();
                    } catch (\Throwable $th) {
                        Notification::make()
                            ->title('Something Went Wrong')
                            ->body('Product is failed to import. Please try again. Message : ' . $th->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\CreateAction::make()
                ->label('New Product')
                ->icon('heroicon-m-plus'),
        ];
    }
}
