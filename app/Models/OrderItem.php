<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type',
        'item_id',
        'quantity'
    ];

    /**
     * Return the specific item
     *
     * @return HasOne
     */
    public function item(): HasOne
    {
        return match ($this->item_type) {
            'pizza' => $this->hasOne(Pizza::class, 'id', 'item_id'),
            'drink' => $this->hasOne(Drink::class, 'id', 'item_id')
        };
    }

    /**
     * Retrieve the order this item belongs to.
     *
     * @return BelongsTo
     */
    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
