<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
            'store',
            'update',
            'destroy',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $products = Product::query()
            ->select('id', 'name', 'category_id', 'sku', 'price', 'quantity')
            ->get()
            ->toArray();

        return Response($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request): Response
    {
        Product::query()->create($request->validated());

        return Response([], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        $product = Product::query()
            ->select('id', 'name', 'category_id', 'sku', 'price', 'quantity')
            ->where('id', '=', $id)
            ->first()
            ->toArray();

        return Response($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, int $id): Response
    {
        $product = Product::query()->find($id);
        $product->update($request->validated());

        return Response([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): Response
    {
        Product::query()
            ->where('id', '=', $id)
            ->delete();

        return Response([]);
    }
}
