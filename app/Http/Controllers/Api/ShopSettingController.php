<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopSettingResource;
use App\Models\ShopSetting;
use Illuminate\Http\Request;

class ShopSettingController extends Controller
{
    public function index()
    {
        $shopSetting = ShopSetting::query()->first();

        return new ShopSettingResource($shopSetting);
    }
}
