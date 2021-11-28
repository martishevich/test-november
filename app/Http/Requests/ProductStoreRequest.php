<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'min:0'],
        ];
    }
}
