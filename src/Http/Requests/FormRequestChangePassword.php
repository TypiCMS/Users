<?php
namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequestChangePassword extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ];
        return $rules;
    }
}
