<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get All Products
     */
    public function index()
    {
        $products = Product::with(['category'])->get();

        return ProductResource::collection($products);
    }

    /**
     * Get Product By Barcode
     */
    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->barcode;

        if (!$barcode) {
            return response()->json(['message' => 'Barcode is not found.'], 404);
        }

        $product = Product::with(['category'])->where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json(['message' => 'Product is not found.'], 404);
        }

        return new ProductResource($product);
    }
}
