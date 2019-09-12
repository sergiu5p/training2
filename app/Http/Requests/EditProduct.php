<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Product;

class EditProduct extends FormRequest
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
        $unique = Rule::unique('products');
        $unique = $unique->ignore(Product::query()->findOrFail(request()->instance()->id));
        return [
            'title' => [
                'bail',
                'required',
                'max:255',
                $unique,
            ],

            'description' => ['required'],

            'price' => [
                'required',
                'numeric'
            ],

            'image' => ['image'],
        ];
    }
}
