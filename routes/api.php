<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PizzaToppingController;
use App\Http\Controllers\PizzaController;
use App\Models\Topping;
use App\Http\Resources\ToppingResource;
use App\Models\Pizza;
use App\Http\Resources\PizzaResource;
use Illuminate\Support\Facades\Route;

Route::get('/toppings', function () {
    return ToppingResource::collection(Topping::all());
});

Route::get('/toppings/{topping}', function (Topping $topping) {
    return $topping->toResource(ToppingResource::class);
});

Route::get('/pizzas', function () {
    return PizzaResource::collection(Pizza::all());
});

Route::get('/pizzas/{pizza}', function (Pizza $pizza) {
    return $pizza->toResource(PizzaResource::class);
});

Route::post('/pizzas/', [PizzaController::class, 'store']);
Route::put('/pizzas/{pizza}/toppings', [PizzaController::class, 'update']);
Route::delete('/pizzas/{pizza}/toppings', [PizzaToppingController::class, 'destroy']);
Route::post('/orders/', [OrderController::class, 'store']);
Route::post('/orders/{order}/items', [OrderController::class, 'addItem']);
Route::delete('/orders/{order}/items/{order_item}', [OrderController::class, 'removeItem']);
Route::put('/orders/{order}', [OrderController::class, 'update']);
Route::post('/orders/{order}/coupon', [OrderController::class, 'applyCoupon']);
Route::post('/orders/{order}/place', [OrderController::class, 'place']);
