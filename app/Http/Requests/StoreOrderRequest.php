<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'billing_name' => 'required|max:255',
            'billing_email' => 'required|email|max:225',
            'billing_phone' => 'required|max:225',
            'billing_street_address' => 'required|max:225',
            'billing_city' => 'required|max:225',
            'billing_zip_code' => 'required|max:225',
            'billing_country' => 'required|max:225',
            'billing_state' => 'required|max:225',
            'shipping_name' => 'required_if:different_address,1|max:255',
            'shipping_email' => 'required_if:different_address,1|email|max:225',
            'shipping_phone' => 'required_if:different_address,1|max:225',
            'shipping_street_address' => 'required_if:different_address,1|max:225',
            'shipping_city' => 'required_if:different_address,1|max:225',
            'shipping_zip_code' => 'required_if:different_address,1|max:225',
            'shipping_country' => 'required_if:different_address,1|max:225',
            'shipping_state' => 'required_if:different_address,1|max:225',
            'payment_method' => 'required'
        ];
    }
}
