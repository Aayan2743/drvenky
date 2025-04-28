<?php

namespace App\Http\Requests;

use App\Enums\Activity;
use App\Enums\OrderType;
use App\Rules\ValidJsonOrder;
use Illuminate\Validation\Rule;
use Smartisan\Settings\Facades\Settings;
use Illuminate\Foundation\Http\FormRequest;

class PosOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_id'     => ['required', 'numeric'],
            'receivedAmount'    =>['required'],
            'payment_method'     => ['required', 'string'],
            'subtotal'        => ['required', 'numeric'],
            'discount'        => ['nullable', 'numeric'],
            'tax'             => ['required', 'numeric'],
            'total'           => ['required', 'numeric'],
            'order_type'      => ['required', 'numeric'],
            'source'          => ['required', 'numeric'],
            'products'        => ['required', 'json', new ValidJsonOrder]
        ];
    }


//     public function withValidator($validator)
// {
//     $validator->after(function ($validator) {
//         $paymentMethod = $this->input('payment_method');

//         if ($paymentMethod === 'upi') {
//             $validator->errors()->add('payment_method', 'UPI payments require additional steps. Please follow the instructions.');
//         } elseif ($paymentMethod === 'card') {
//             $validator->errors()->add('payment_method', 'Card payments require CVV details.');
//         }
//     });
// }




    public function messages(): array
{
    return [
      
        'payment_method.required' => 'Please select a payment method.',
        'payment_method.required' => 'Please select a Missing Field.',
       
    ];
}
}