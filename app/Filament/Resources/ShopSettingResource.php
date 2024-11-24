<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopSettingResource\Pages;
use App\Filament\Resources\ShopSettingResource\RelationManagers;
use App\Models\ShopSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShopSettingResource extends Resource
{
    protected static ?string $model = ShopSetting::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-storefront';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('shop_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Shop Name')
                            ->prefixIcon('heroicon-m-building-storefront'),
                        Forms\Components\TextInput::make('shop_phone')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->label('Shop Phone')
                            ->prefixIcon('heroicon-m-phone'),
                    ]),
                Forms\Components\Textarea::make('shop_address')
                    ->required()
                    ->columnSpanFull()
                    ->label('Shop Address'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shop_name')
                    ->searchable()
                    ->label('Shop Name'),
                Tables\Columns\TextColumn::make('shop_phone')
                    ->searchable()
                    ->label('Shop Phone'),
                Tables\Columns\TextColumn::make('shop_address')
                    ->searchable()
                    ->label('Shop Address'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageShopSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return ShopSetting::query()->count() < 1;
    }
}
