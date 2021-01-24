<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigRequest extends FormRequest
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
            'stakeLimit' => 'required|numeric|min:1|max:10000000',
            'timeDuration' => 'required|numeric|min:5|max:3600',
            'hotAmountPctg' => 'required|numeric|min:1|max:100',
            'restrExpiry' => 'required|numeric'
        ];
    }
}