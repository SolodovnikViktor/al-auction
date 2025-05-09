<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'image_preview' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'vin' => 'required|string',
            'brand' => 'required|string|max:20',
            'model' => 'required|string|max:20',
            'year_release' => 'required|integer|max:2050',
            'color_id' => 'required|integer',
            'mileage' => 'required|integer|max:5000000',
            'fuel' => 'required|string|max:10',
            'drive_id' => 'required|integer',
            'body_type_id' => 'required|integer',
            'transmission_id' => 'required|integer',
            'engine_capacity' => 'required|numeric|max:100',
            'horsepower' => 'required|integer|max:1000',
            'price' => 'required|integer|max:16700000',
            'up_price' => 'nullable|integer|max:100000000',
            'description' => 'required|string|max:3000',
            'user_id' => 'integer',
            'is_published' => 'nullable|boolean',
        ];
    }
}
