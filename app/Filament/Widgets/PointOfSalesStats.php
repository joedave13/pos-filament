<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PointOfSalesStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Product', Product::query()->count())
                ->description('Total products')
                ->icon('heroicon-m-shopping-bag')
                ->color('amber'),
            Stat::make('Order', Order::query()->count())
                ->description('Total orders')
                ->icon('heroicon-m-shopping-cart')
                ->color('primary'),
            Stat::make('Sales', 'Rp. ' . number_format(Order::query()->sum('total_price'), 0, ',', '.'))
                ->description('Total sales')
                ->icon('heroicon-m-currency-dollar')
                ->color('sky'),
            Stat::make('Expense', 'Rp. ' . number_format(Expense::query()->sum('amount'), 0, ',', '.'))
                ->description('Total expenses')
                ->icon('heroicon-m-credit-card')
                ->color('red')
        ];
    }
}
