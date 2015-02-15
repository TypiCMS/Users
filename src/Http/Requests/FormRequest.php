<?php
namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'email'                 => 'required|email|unique:users',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ];
        return $rules;
    }
}
