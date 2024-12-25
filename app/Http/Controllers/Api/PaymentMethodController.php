<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Get All Payment Methods
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::query()->get();

        return PaymentMethodResource::collection($paymentMethods);
    }
}
