<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'delivery_address' => 'required|string|min:10|max:500',
            'phone' => 'required|string|regex:/^[0-9\-\+\s()]+$/',
            'notes' => 'nullable|string|max:500',
        ];
    }
    public function messages()
    {
        return [
            'delivery_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'delivery_address.min' => 'Địa chỉ giao hàng phải có ít nhất 10 ký tự.',
            'delivery_address.max' => 'Địa chỉ giao hàng không được vượt quá 500 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'notes.max' => 'Ghi chú không được quá 500 ký tự',
        ];
    }
}
