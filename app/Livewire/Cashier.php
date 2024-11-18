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

class Cashier extends Component implements HasForms
{
    use InteractsWithForms;

    public $search = '';
    public $customerName = '';

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
}
