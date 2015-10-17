<?php

namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequestPassword extends AbstractFormRequest
{
    public function rules()
    {
        $rules = [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed',
        ];

        return $rules;
    }
}
