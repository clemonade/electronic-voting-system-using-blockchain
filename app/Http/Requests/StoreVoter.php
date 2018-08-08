<?php

namespace App\Http\Requests;

use App\Rules\Nric;
use Illuminate\Foundation\Http\FormRequest;

class StoreVoter extends FormRequest
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
            'nric' => [
                'required',
                new Nric(),
            ],
            'gender' => 'required',
            'state' => 'required',
            'federalconstituency' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'nric.required' => 'NRIC is required.',
            'gender.required' => 'Gender is required.',
            'state.required' => 'State is required.',
            'federalconstituency.required' => 'Federal Constituency is required.',
        ];
    }
}
