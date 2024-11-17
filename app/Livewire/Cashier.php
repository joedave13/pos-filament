<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Cashier extends Component
{
    public $search = '';

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
}
