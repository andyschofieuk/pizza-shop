<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use App\Models\PizzaTopping;

class ToppingResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public $attributes = [
        'name',
        'price'
    ];

    /**
     * The resource's relationships.
     */
    public $relationships = [
        'pizza' => PizzaTopping::class
    ];
}
