<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $products_name = fake()->randomElement(['product 1', 'product 2', 'product 3']); 
        $prices = function($product){
            if ($product == 'product 1') {
                return 1000;
            }else if($product == 'product 2') {
                return 2000;
            }else{
                return 3000;
            }
        };
        
        return [
            // 'name' => fake()->name(),
            // 'price' => fake()->numberBetween(1000, 10000),
            'name' => $products_name,
            'price' => $prices($products_name),
        ];
    }
}
