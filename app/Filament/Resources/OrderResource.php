<?php

namespace App\Filament\Resources;

use App\Enums\OrderGender;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-cart';

    protected static ?string $navigationGroup = 'Transaction';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->description('Information about the user / buyer.')
                    ->icon('heroicon-s-user')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-s-user'),
                        Forms\Components\Radio::make('gender')
                            ->label('Gender')
                            ->enum(OrderGender::class)
                            ->options(OrderGender::class)
                            ->required()
                            ->inline()
                            ->inlineLabel(false),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-s-at-symbol'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->prefixIcon('heroicon-s-phone'),
                    ]),
                Forms\Components\Section::make('Ordered Product')
                    ->description('List of products that ordered by user.')
                    ->icon('heroicon-s-squares-2x2')
                    ->schema([
                        Forms\Components\Repeater::make('orderDetails')
                            ->relationship()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::updateOrderTotalPrice($set, $get);
                            })
                            ->columns([
                                'md' => 12
                            ])
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name', fn(Builder $query) => $query->where('stock', '!=', 0)->where('is_active', true))
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(['md' => 4])
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = Product::query()->find($state);
                                        $set('stock', $product->stock);
                                        $set('price', $product->price);
                                    })
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                Forms\Components\TextInput::make('stock')
                                    ->label('Stock')
                                    ->default(0)
                                    ->readOnly()
                                    ->columnSpan(['md' => 1]),
                                Forms\Components\TextInput::make('price')
                                    ->label('Unit Price')
                                    ->default(0)
                                    ->readOnly()
                                    ->columnSpan(['md' => 3]),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->minValue(0)
                                    ->columnSpan(['md' => 1])
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state > $get('stock')) {
                                            $set('quantity', $get('stock'));
                                            Notification::make()
                                                ->title('Insufficient Stock')
                                                ->body('Your quantity is greater than product stock.')
                                                ->warning()
                                                ->send();
                                        }

                                        $productPrice = $get('price') ?? 0;
                                        $productSubtotal = $state * $productPrice;

                                        $set('subtotal', $productSubtotal);
                                    }),
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->default(0)
                                    ->readOnly()
                                    ->columnSpan(['md' => 3]),
                            ])
                    ]),
                Forms\Components\Section::make('Additional Information')
                    ->description('Additional informations that requested by user.')
                    ->icon('heroicon-s-list-bullet')
                    ->schema([
                        Forms\Components\Textarea::make('note')
                            ->columnSpanFull()
                            ->rows(4),
                    ]),
                Forms\Components\Section::make('Payment Information')
                    ->description('Payment information of the order.')
                    ->icon('heroicon-s-credit-card')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('total_price')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->readOnly(),
                        Forms\Components\Select::make('payment_method_id')
                            ->relationship('paymentMethod', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-s-credit-card')
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $paymentMethod = PaymentMethod::query()->find($state);

                                if (!$paymentMethod->is_cash) {
                                    $set('paid_amount', $get('total_price'));
                                    $set('change_amount', $get('total_price'));
                                } else {
                                    $set('paid_amount', 0);
                                    $set('change_amount', 0);
                                }
                            }),
                        Forms\Components\TextInput::make('paid_amount')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->readOnly(fn(Get $get): bool => $get('payment_method_id') ? !PaymentMethod::query()->find($get('payment_method_id'))->is_cash : false)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $paymentMethod = PaymentMethod::query()->find($get('payment_method_id'));

                                if ($paymentMethod->is_cash) {
                                    if ($state < $get('total_price')) {
                                        $set('paid_amount', 0);
                                        $set('change_amount', 0);
                                        Notification::make()
                                            ->title('Not Enough Money')
                                            ->body("You don't have enough money to make the payment.")
                                            ->warning()
                                            ->send();

                                        return;
                                    }

                                    $changeAmount = $state != 0 ? ($state - $get('total_price')) : 0;

                                    $set('change_amount', $changeAmount);
                                }
                            }),
                        Forms\Components\TextInput::make('change_amount')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->readOnly(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('change_amount')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    protected static function updateOrderTotalPrice(Set $set, Get $get): void
    {
        $selectedProducts = collect($get('orderDetails'))->filter(fn($item) => !empty($item['product_id']));

        $unitPrices = Product::query()->find($selectedProducts->pluck('product_id'))->pluck('price', 'id');

        $totalPrice = $selectedProducts->reduce(function ($total, $product) use ($unitPrices) {
            return $total + ($unitPrices[$product['product_id']] * $product['quantity']);
        }, 0);

        $set('total_price', $totalPrice);
    }
}
