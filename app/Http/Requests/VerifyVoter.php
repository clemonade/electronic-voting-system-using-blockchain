<?php

namespace App\Http\Requests;

use App\Rules\Nric;
use Illuminate\Foundation\Http\FormRequest;

class VerifyVoter extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'nric.required' => 'NRIC is required.',
        ];
    }
}
