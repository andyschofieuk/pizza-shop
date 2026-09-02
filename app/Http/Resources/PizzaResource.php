<?php

namespace App\Http\Resources;

use App\Models\PizzaTopping;
use App\Models\Topping;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use App\Models\Pizza;

class PizzaResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'name',
        'size'
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [
        'toppings' => Topping::class,
    ];

    public function toRelationships(Request $request)
    {
        return [
            'toppings' => fn () => ToppingResource::collection($this->whenLoaded('toppings'))
        ];
    }
}
