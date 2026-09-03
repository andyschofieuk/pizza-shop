<?php

namespace Database\Seeders;

use App\Models\Topping;
use App\Models\Pizza;
use App\Models\PizzaTopping;
use Illuminate\Database\Seeder;

class PizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toppingNames = [
            'Pepperoni',
            'Mushrooms',
            'Onions',
            'Sausage',
            'Bacon',
            'Extra Cheese',
            'Tomato',
            'Olives',
            'Chicken',
            'Anchovies'
        ];

        foreach ($toppingNames as $toppingName) {
            Topping::factory()->create(['name' => $toppingName])->id;
        }
        $toppings = Topping::all('id')->toArray();

        $pizzaNames = [
            'Margherita',
            'Pepperoni',
            'Hawaiian',
            'Vegan'
        ];
        foreach ($pizzaNames as $pizzaName) {
            Pizza::factory(10)->create(['name' => $pizzaName . ' Pizza']);
        }
        $pizzas = Pizza::all();

        foreach ($pizzas as $pizza) {
            $amount = rand(1, 5);
            for($i = 0; $i < $amount; $i++) {
                PizzaTopping::factory()->create([
                    'pizza_id' => $pizza['id'],
                    'topping_id' => $toppings[array_rand($toppings)]['id'],
                ]);
            }

            $pizza->calculatePrice();
        }
    }
}
