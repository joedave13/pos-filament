<?php

namespace App\Models;

use App\Enums\OrderGender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'phone',
        'total_price',
        'note',
        'payment_method_id',
        'paid_amount',
        'change_amount'
    ];

    protected function casts(): array
    {
        return [
            'gender' => OrderGender::class,
            'total_price' => 'integer',
            'payment_method_id' => 'integer',
            'paid_amount' => 'integer',
            'change_amount' => 'integer'
        ];
    }
}
