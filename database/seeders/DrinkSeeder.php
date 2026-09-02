<?php

namespace Database\Seeders;

use App\Models\Drink;
use Illuminate\Database\Seeder;

class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Drink::factory()->make(['name' => 'Coca Cola'])->save();
        Drink::factory()->make(['name' => 'Pepsi'])->save();
        Drink::factory()->make(['name' => 'Sprite'])->save();
        Drink::factory()->make(['name' => 'Fanta'])->save();
    }
}
