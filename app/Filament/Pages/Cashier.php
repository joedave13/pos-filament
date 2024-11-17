<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Cashier extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    protected static string $view = 'filament.pages.cashier';

    protected static ?string $navigationGroup = 'Transaction';
}
