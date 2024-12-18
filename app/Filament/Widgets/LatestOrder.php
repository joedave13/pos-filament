<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\PaymentMethod;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrder extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::with(['orderDetails', 'paymentMethod'])->latest()->take(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Order Date'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_details_count')
                    ->counts('orderDetails')
                    ->label('Total Products'),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp. ')
                    ->sortable()
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->badge()
                    ->color(function (string $state): string {
                        $paymentMethod = PaymentMethod::query()->where('name', $state)->first();

                        return match ($paymentMethod->is_cash) {
                            true => 'primary',
                            false => 'warning',
                        };
                    }),
            ]);
    }
}
