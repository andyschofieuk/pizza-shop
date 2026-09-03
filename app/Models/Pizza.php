<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Size;

class Pizza extends Model
{
    /** @use HasFactory<\Database\Factories\PizzaFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'custom'
    ];

    const SMALL_PRICE = 5;
    const MEDIUM_PRICE = 10;
    const LARGE_PRICE = 15;

    /**
     * Return all toppings for the pizza
     *
     * @return HasManyThrough
     */
    public function toppings(): HasManyThrough
    {
        return $this->hasManyThrough(
            Topping::class,
            PizzaTopping::class,
            'pizza_id',
            'id',
            'id',
            'topping_id'
        );
    }

    /**
     * Calculate price of pizza based on size and toppings
     *
     * @return $this
     */
    public function calculatePrice(): self
    {
        $toppings = $this->toppings()->pluck('price');

        $extraPrice = collect($toppings)
            ->sort()
            ->slice(3)
            ->sum();

        $size = $this->size;
        $sizePrice = match($size) {
            'small' => self::SMALL_PRICE,
            'medium' => self::MEDIUM_PRICE,
            'large' => self::LARGE_PRICE
        };

        $price = $sizePrice + $extraPrice;
        $this->price = $price;
        $this->save();

        return $this;
    }
}
