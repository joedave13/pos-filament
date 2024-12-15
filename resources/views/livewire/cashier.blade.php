<div>
    <div class="grid grid-cols-1 dark:bg-gray-900 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="mb-4">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search product..."
                    class="w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            </div>
            <div class="flex-grow">
                <div class="grid grid-cols-8 sm:grid-cols-3 md:grid-cols-8 lg:grid-cols- gap-4">
                    @foreach ($products as $product)
                        <div wire:click="addToCart({{ $product->id }})"
                            class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow cursor-pointer">
                            <img src="{{ $product->image ? Storage::url($product->image) : 'https://placehold.co/600?text=No+Image' }}"
                                alt="Product Image" class="w-full h-32 object-cover rounded-lg mb-2">
                            <h3 class="font-semibold mb-2 truncate">{{ $product->name }}</h3>
                            <p class="text-gray-950 dark:text-gray-400 text-xs text-right">Rp.
                                {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-gray-600 dark:text-gray-400 text-xs text-right">Stock: {{ $product->stock }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="py-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
        <div class="md:col-span-1 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">

            <div class="py-4">
                <h3 class="text-lg font-semibold text-center">Total: Rp. {{ number_format($totalPrice, 0, ',', '.') }}
                </h3>
            </div>
            <div class="mb-4">
                @foreach ($cartItems as $cartKey => $cartItem)
                    <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <div class="flex items-center">
                            <img src="{{ $cartItem['product_image'] }}" alt="Product Image"
                                class="w-10 h-10 object-cover rounded-lg mr-2">
                            <div class="px-2">
                                <h3 class="text-sm font-semibold truncate">{{ $cartItem['product_name'] }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-xs">Rp
                                    {{ number_format($cartItem['product_price'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <x-filament::button color="warning"
                                wire:click="decreaseQuantity({{ $cartItem['product_id'] }})">-</x-filament::button>
                            <span class="px-4">{{ $cartItem['product_quantity'] }}</span>
                            <x-filament::button color="success" style="margin-right: 0.25rem;"
                                wire:click="increaseQuantity({{ $cartItem['product_id'] }})">+</x-filament::button>
                            <x-filament::button color="danger"
                                wire:click="deleteFromCart({{ $cartKey }})">x</x-filament::button>
                        </div>
                    </div>
                @endforeach
            </div>

            <form wire:submit="checkout">
                {{ $this->form }}

                <x-filament::button type="submit" class="w-full" icon="heroicon-m-shopping-cart">
                    Checkout
                </x-filament::button>
            </form>

            <div class="mt-2">

            </div>
        </div>
    </div>
</div>
