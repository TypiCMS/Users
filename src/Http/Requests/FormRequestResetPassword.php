<?php
namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Http\Requests\AbstractFormRequest;

class FormRequestResetPassword extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'email' => 'required|email',
        ];
        return $rules;
    }
}
