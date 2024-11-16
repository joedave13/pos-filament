<?php

namespace App\Models;

use App\Enums\OrderGender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
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

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
