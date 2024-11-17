<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'stock',
        'price',
        'is_active',
        'image',
        'barcode'
    ];

    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'stock' => 'integer',
            'price' => 'integer',
            'is_active' => 'boolean'
        ];
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeSearch(Builder $query, string $value): void
    {
        $query->where('name', 'like', "%{$value}%");
    }
}
