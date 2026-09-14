<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_code',
        'order_date',
        'completion_date',
        'status',
        'total_price',
    ];

    protected $casts = [
        'order_date'      => 'date',
        'completion_date' => 'date',
        'total_price'     => 'decimal:2',
    ];

    public const STATUS = ['pending', 'processing', 'ready', 'completed'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_details')
                    ->withPivot(['qty', 'subtotal'])
                    ->withTimestamps();
    }

    public function recalculateTotal(): void
    {
        $this->update([
            'total_price' => $this->orderDetails()->sum('subtotal'),
        ]);
    }
}