<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\CreatesApplication;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function test_index(): void
    {
        $count = 5;

        Category::factory()
            ->count($count)
            ->create();

        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has($count)
                ->first(fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                    ])
                )
            );
    }

    public function test_exact_category(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->first(fn (AssertableJson $json) =>
                $json->where('id', $category->id)->where('name', $category->name)
            )
        );
    }
}
