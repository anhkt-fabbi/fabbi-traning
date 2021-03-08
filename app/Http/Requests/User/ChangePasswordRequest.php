<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            "oldPassword" => 'required|min:6|max:64',
            "newPassword" => 'required|min:6|max:64|required_with:confirmPassword',
            "confirmPassword" => 'required|min:6|max:64'
        ];
    }
}
