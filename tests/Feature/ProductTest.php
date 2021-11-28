<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use OwenIt\Auditing\Models\Audit;
use Tests\CreatesApplication;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function test_category(): void
    {
        $count = 5;

        Product::factory()
            ->count($count)
            ->create();

        $response = $this->getJson(route('products.index'));

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has($count)
                ->first(fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'category_id' => 'integer',
                        'sku' => 'string',
                        'price' => 'float|integer',
                        'quantity' => 'integer',
                    ])
                )
        );
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Category $category */
        $category = Category::factory()->create();

        $data = [
            'name' => 'some name',
            'category_id' => $category->id,
            'sku' => 'abc',
            'price' => 2,
            'quantity' => 3,
        ];

        $response = $this
            ->actingAs($user)
            ->postJson(route('products.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas((new Product())->getTable(), $data);
    }

    public function test_show(): void
    {
        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $response = $this->getJson(route('products.show', [$product->id]));

        $response->assertStatus(200);

        $response->assertExactJson([
            'id' => $product->id,
            'name' => $product->name,
            'category_id' => $product->category_id,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => $product->quantity,
        ]);
    }

    public function test_update(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $dataToUpdate = [
            'name' => 'new name',
            'category_id' => $product->category_id,
            'sku' => 'abc',
            'price' => 2,
            'quantity' => 3,
        ];

        $response = $this
            ->actingAs($user)
            ->putJson(route('products.update', [$product->id]), $dataToUpdate);

        $response->assertStatus(200);

        $this->assertDatabaseHas((new Product())->getTable(), [
            'id' => $product->id,
            'name' => $dataToUpdate['name'],
            'category_id' => $dataToUpdate['category_id'],
            'sku' => $dataToUpdate['sku'],
            'price' => $dataToUpdate['price'],
            'quantity' => $dataToUpdate['quantity'],
        ]);

        $this->assertDatabaseMissing((new Product())->getTable(), [
            'id' => $product->id,
            'name' => $product->name,
            'category_id' => $product->category_id,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => $product->quantity,
        ]);

        $this->assertDatabaseHas((new Audit())->getTable(), [
            'user_id' => $user->id,
            'event' => 'updated',
        ]);
    }

    public function test_destroy(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $response = $this
            ->actingAs($user)
            ->deleteJson(route('products.destroy', [$product->id]));

        $response->assertStatus(200);

        $this->assertDeleted($product);
    }
}
