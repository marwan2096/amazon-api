<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'sku'            => 'required|string|unique:products',
            'description'    => 'nullable|string',
            'is_active'      => 'sometimes|boolean',
            'categories'     => 'sometimes|array',
            'categories.*'   => 'exists:categories,id',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
