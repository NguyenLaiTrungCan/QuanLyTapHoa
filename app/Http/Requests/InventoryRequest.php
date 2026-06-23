<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin only check can be done in controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id|unique:inventories,product_id',
            'quantity' => 'required|integer|min:0|max:999999',
            'location' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm là bắt buộc.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'product_id.unique' => 'Sản phẩm này đã có trong kho hàng.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải từ 0 trở lên.',
            'quantity.max' => 'Số lượng quá lớn.',
            'location.string' => 'Vị trí phải là chuỗi ký tự.',
            'location.max' => 'Vị trí không được vượt quá 255 ký tự.',
        ];
    }
}
