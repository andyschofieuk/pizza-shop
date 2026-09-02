<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Pizza extends Model
{
    /** @use HasFactory<\Database\Factories\PizzaFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'custom'
    ];

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
}
