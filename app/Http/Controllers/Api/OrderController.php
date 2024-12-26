<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderDetails', 'orderDetails.product', 'paymentMethod'])->latest()->paginate(10);

        return OrderResource::collection($orders);
    }
}
