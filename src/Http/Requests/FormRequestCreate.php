<?php

namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequestCreate extends AbstractFormRequest
{
    public function rules()
    {
        $rules = [
            'email'                 => 'required|email|unique:users',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ];

        return $rules;
    }
}
