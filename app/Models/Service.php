<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price_per_kg', 'unit'];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
    ];

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}