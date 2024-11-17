<?php

namespace App\Observers;

use App\Models\OrderDetail;
use App\Models\Product;

class OrderDetailObserver
{
    /**
     * Handle the OrderDetail "created" event.
     */
    public function created(OrderDetail $orderDetail): void
    {
        $orderDetail->product()->decrement('stock', $orderDetail->quantity);
    }

    /**
     * Handle the OrderDetail "updated" event.
     */
    public function updated(OrderDetail $orderDetail): void
    {
        $product = Product::query()->find($orderDetail->product_id);
        $originalQuantity = $orderDetail->getOriginal('quantity');
        $newQuantity = $orderDetail->quantity;

        if ($originalQuantity != $newQuantity) {
            $product->increment('stock', $originalQuantity);
            $product->decrement('stock', $newQuantity);
        }
    }

    /**
     * Handle the OrderDetail "deleted" event.
     */
    public function deleted(OrderDetail $orderDetail): void
    {
        $product = Product::query()->find($orderDetail->product_id);
        $product->increment('stock', $orderDetail->quantity);
    }

    /**
     * Handle the OrderDetail "restored" event.
     */
    public function restored(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the OrderDetail "force deleted" event.
     */
    public function forceDeleted(OrderDetail $orderDetail): void
    {
        //
    }
}
