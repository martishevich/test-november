<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'category_id' => Category::factory(),
            'sku' => $this->faker->name,
            'price' => $this->faker->randomNumber(),
            'quantity' => $this->faker->randomNumber(),
        ];
    }
}
