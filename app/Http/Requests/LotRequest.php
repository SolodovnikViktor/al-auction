<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotRequest extends FormRequest
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
            'brand_id' => 'required|integer',
            'model_id' => 'required|integer',
            'vin' => 'required|string',
            'year_release' => 'required|integer|max:2050',
            'color_id' => 'required|integer',
            'mileage' => 'required|integer|max:5000000',
            'fuel_id' => 'required|integer',
            'wheel_id' => 'required|integer',
            'drive_id' => 'required|integer',
            'body_type_id' => 'required|integer',
            'transmission_id' => 'required|integer',
            'engine_capacity' => 'required|numeric|max:100',
            'horsepower' => 'required|integer|max:1000',
            'price' => 'required|integer|max:16700000',
            'description' => 'required|string|max:3000',
            'user_id' => 'integer',
            'is_published' => 'nullable|boolean',
        ];
    }
}
