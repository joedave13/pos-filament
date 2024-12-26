<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderDetails', 'orderDetails.product', 'paymentMethod'])->latest()->paginate(10);

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        foreach ($request->order_items as $item) {
            $productItem = Product::query()->find($item['product_id']);

            if ($productItem->stock < $item['quantity']) {
                return response()->json(['message' => 'Insufficient product stock: ' . $productItem->name], 400);
            }
        }

        DB::beginTransaction();

        try {
            $validatedOrderRequest = $request->safe()->except(['order_items']);
            $validatedOrderItemRequest = $request->safe()->only(['order_items']);

            $paymentMethod = PaymentMethod::query()->find($validatedOrderRequest['payment_method_id']);

            $validatedOrderRequest['paid_amount'] = $paymentMethod->is_cash ? $validatedOrderRequest['paid_amount'] : $validatedOrderRequest['total_price'];
            $validatedOrderRequest['change_amount'] = $paymentMethod->is_cash ? ($validatedOrderRequest['paid_amount'] - $validatedOrderRequest['total_price']) : 0;

            $order = Order::query()->create($validatedOrderRequest);

            foreach ($validatedOrderItemRequest['order_items'] as $key => $item) {
                $order->orderDetails()->create($item);
            }

            DB::commit();

            return new OrderResource($order->load(['orderDetails', 'orderDetails.product', 'paymentMethod']));
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => 'Something went wrong: ' . $th->getMessage()], 500);
        }
    }
}
