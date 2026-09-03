<?php

namespace App\Http\Controllers;

use App\Http\Resources\PizzaResource;
use App\Models\Pizza;
use App\Http\Requests\StorePizzaRequest;
use App\Http\Requests\UpdatePizzaRequest;
use App\Models\PizzaTopping;

class PizzaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePizzaRequest $request)
    {
        $name = 'Custom Pizza ' . time();

        $pizza = Pizza::create([
            'name' => $name,
            'size' => $request->size,
            'custom' => true
        ]);

        $pizza = $pizza->calculatePrice();

        $pizza->load('toppings');
        return new PizzaResource($pizza);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pizza $pizza)
    {
        $pizza->load('toppings');

        return new PizzaResource($pizza);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pizza $pizza)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePizzaRequest $request, Pizza $pizza)
    {
        if (!$pizza->custom) {
            return response()->json(['message' => 'Pizza cannot be updated'], 400);
        }

        $currentToppings = $pizza->toppings->pluck('id')->toArray();
        $newToppings = $request->topping_id;

        $toDeleteTopping = array_diff($currentToppings, $newToppings);
        if (count($toDeleteTopping) > 0) {
            foreach ($toDeleteTopping as $topping) {
                PizzaTopping::where('pizza_id', $pizza->id)->where('topping_id', $topping)->delete();
            }
        }

        foreach ($newToppings as $topping) {
            $pizzaTopping = PizzaTopping::where('pizza_id', $pizza->id)->where('topping_id', $topping)->first();
            if (!$pizzaTopping) {
                PizzaTopping::create([
                    'pizza_id' => $pizza->id,
                    'topping_id' => $topping
                ]);
            }
        }

        $pizza = $pizza->calculatePrice();

        $pizza->load('toppings');
        return new PizzaResource($pizza);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pizza $pizza)
    {
        //
    }
}
