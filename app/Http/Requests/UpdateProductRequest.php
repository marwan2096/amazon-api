<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'name'           => 'sometimes|string|max:255',
            'price'          => 'sometimes|numeric|min:0',
            'stock'          => 'sometimes|integer|min:0',
            'sku'            => "sometimes|string|unique:products,sku,{$productId}",
            'description'    => 'sometimes|nullable|string',
            'is_active'      => 'sometimes|boolean',
            'categories'     => 'sometimes|array',
            'categories.*'   => 'exists:categories,id',
            'image'          => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
