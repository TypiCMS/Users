<?php

namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequestLogin extends AbstractFormRequest
{
    public function rules()
    {
        $rules = [
            'email'      => 'required|email',
            'password'   => 'required',
        ];

        return $rules;
    }
}
