<?php

namespace App\Livewire;

use App\Enums\OrderGender;
use App\Models\PaymentMethod;
use App\Models\Product;
use Filament\Forms\Form;
use Livewire\Component;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class Cashier extends Component implements HasForms
{
    use InteractsWithForms;

    public $search = '';
    public $customerName = '';
    public $cartItems = [];
    public $totalPrice = 0;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render()
    {
        $products = Product::query()
            ->where('stock', '!=', 0)
            ->where('is_active', true)
            ->search($this->search)
            ->orderBy('name')
            ->paginate(9);

        return view('livewire.cashier', compact('products'));
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Checkout Form')
                ->schema([
                    Forms\Components\TextInput::make('customer_name')
                        ->required()
                        ->maxLength(255)
                        ->default(fn() => $this->customerName)
                        ->label('Customer Name'),
                    Forms\Components\Radio::make('customer_gender')
                        ->required()
                        ->enum(OrderGender::class)
                        ->options(OrderGender::class)
                        ->inline()
                        ->inlineLabel(false)
                        ->label('Customer Gender'),
                    Forms\Components\TextInput::make('total_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->label('Total Price'),
                    Forms\Components\Select::make('payment_method_id')
                        ->label('Payment Method')
                        ->options(PaymentMethod::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->preload()
                ])
        ]);
    }

    public function addToCart(int $productId)
    {
        $product = Product::query()->findOrFail($productId);

        if ($product->stock <= 0) {
            Notification::make()
                ->title('Insufficient Product Stock')
                ->body("Product's stock is not enough.")
                ->warning()
                ->send();

            return;
        }

        $existingCartItemKey = null;

        foreach ($this->cartItems as $key => $value) {
            if ($value['product_id'] == $productId) {
                $existingCartItemKey = $key;
                break;
            }
        }

        if ($existingCartItemKey !== null) {
            $this->cartItems[$existingCartItemKey]['product_quantity']++;
        } else {
            $this->cartItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => Storage::url($product->image),
                'product_quantity' => 1
            ];
        }

        Session::put('cartItems', $this->cartItems);

        Notification::make()
            ->title('Success')
            ->body("Product's added successfully.")
            ->success()
            ->send();
    }
}
