<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use Filament\Actions;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning')
                ->label('Edit Order')
                ->icon('heroicon-m-pencil'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('User Information')
                ->description('Information about the user / buyer.')
                ->columns(2)
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('gender'),
                    TextEntry::make('email')->default('-'),
                    TextEntry::make('phone')->default('-'),
                ])
                ->icon('heroicon-m-user'),
            Section::make('Ordered Product')
                ->description('List of products that ordered by user.')
                ->schema([
                    RepeatableEntry::make('orderDetails')
                        ->hiddenLabel()
                        ->columns(['md' => 12])
                        ->schema([
                            TextEntry::make('product.name')->columnSpan(['md' => 4]),
                            TextEntry::make('quantity')->columnSpan(['md' => 2]),
                            TextEntry::make('product.price')
                                ->numeric(thousandsSeparator: '.')
                                ->prefix('Rp. ')
                                ->columnSpan(['md' => 3])
                                ->label('Price'),
                            TextEntry::make('sub_total')
                                ->numeric(thousandsSeparator: '.')
                                ->prefix('Rp. ')
                                ->columnSpan(['md' => 3])
                                ->state(function (OrderDetail $record) {
                                    return $record->price * $record->quantity;
                                })
                                ->label('Sub Total'),
                        ])
                ])
                ->icon('heroicon-m-squares-2x2'),
            Section::make('Additional Information')
                ->description('Additional informations that requested by user.')
                ->schema([
                    TextEntry::make('note')
                ])
                ->icon('heroicon-m-list-bullet'),
            Section::make('Payment Information')
                ->description('Payment information of the order.')
                ->columns(2)
                ->schema([
                    TextEntry::make('total_price')
                        ->numeric(thousandsSeparator: '.')
                        ->prefix('Rp. '),
                    TextEntry::make('paymentMethod.name')
                        ->badge()
                        ->color(function (string $state): string {
                            $paymentMethod = PaymentMethod::query()->where('name', $state)->first();

                            return match ($paymentMethod->is_cash) {
                                true => 'primary',
                                false => 'warning',
                            };
                        }),
                    TextEntry::make('paid_amount')
                        ->numeric(thousandsSeparator: '.')
                        ->prefix('Rp. '),
                    TextEntry::make('change_amount')
                        ->numeric(thousandsSeparator: '.')
                        ->prefix('Rp. '),
                ])
                ->icon('heroicon-m-credit-card')
        ]);
    }
}
