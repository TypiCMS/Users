<?php
namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'email'                 => 'required|email|unique:users,email,' . $this->id,
            'first_name'            => 'required',
            'last_name'             => 'required',
            'password'              => 'min:8|confirmed',
        ];
        return $rules;
    }
}
