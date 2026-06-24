<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Auth check done in controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'quantity' => 'required|integer|min:1|max:999999',
        ];

        if ($this->isMethod('POST') || $this->routeIs('cart.add') || $this->is('api/cart/add')) {
            $rules['product_id'] = 'required|integer|exists:products,id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm là bắt buộc.',
            'product_id.integer' => 'ID sản phẩm phải là số.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải từ 1 trở lên.',
            'quantity.max' => 'Số lượng quá lớn.',
        ];
    }
}
