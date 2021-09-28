<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountCouponRequest extends FormRequest
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
            "name" => 'required|max:255',
            "code" => 'required',
            "min_spend" => 'required',
            "max_discount" => 'required',
            "discount_percentage" => 'required',
            "start_date" => 'required|date|after_or_equal:today',
            "start_time" => 'required',
            "expire_date" => 'required|date|after_or_equal:start_date',
            "expire_time" => 'required'
        ];
    }
}
