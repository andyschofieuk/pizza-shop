<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PizzaTopping extends Model
{
    /** @use HasFactory<\Database\Factories\PizzaToppingFactory> */
    use HasFactory;

    protected $fillable = [
        'pizza_id',
        'topping_id'
    ];

    /**
     * Retrieve pizza this pizza topping belongs to
     *
     * @return BelongsTo
     */
    public function pizza(): BelongsTo
    {
        return $this->belongsTo(Pizza::class);
    }

    /**
     * Retrieve topping this pizza topping belongs to
     *
     * @return BelongsTo
     */
    public function topping(): BelongsTo
    {
        return $this->belongsTo(Topping::class);
    }
}
