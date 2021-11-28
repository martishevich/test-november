<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class TotalValueProductTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function test_total_value(): void
    {
        /**
         * @var Product $product1
         */
        $product1 = Product::factory()
            ->create(['price' => 2, 'quantity' => 3]);

        /**
         * @var Product $product2
         */
        $product2 = Product::factory()
            ->create(['price' => 4, 'quantity' => 5]);

        $response = $this->getJson(route('products.getTotalValue'));

        $response->assertStatus(200);

        $total = $product1->price * $product1->quantity + $product2->price * $product2->quantity;
        $response->assertExactJson(['total' => $total]);
    }
}
