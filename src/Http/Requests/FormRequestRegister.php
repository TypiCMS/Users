<?php

namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequestRegister extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'password' => 'required|min:8|max:255|confirmed',
        ];
    }
}
