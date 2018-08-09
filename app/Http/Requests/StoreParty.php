<?php

namespace App\Http\Requests;

use App\Rules\Abbreviation;
use Illuminate\Foundation\Http\FormRequest;

class StoreParty extends FormRequest
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
            'name' => 'required|max:100',
            'abbreviation' => [
                'required',
                new Abbreviation(),

            ],
            'image' => 'required|mimes:jpeg,bmp,png',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'abbreviation.required' => 'Abbreviation is required.',
            'image.required' => 'Logo is required.',
        ];
    }
}
