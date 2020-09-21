<?php

namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|max:255|unique:users,email,'.$this->id,
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'password' => 'nullable|min:8|max:255|confirmed',
            'street' => 'nullable|max:255',
            'number' => 'nullable|max:255',
            'box' => 'nullable|max:255',
            'postal_code' => 'nullable|max:255',
            'city' => 'nullable|max:255',
            'country' => 'nullable|max:255',
            'phone' => 'nullable|max:100',
            'locale' => 'nullable|size:2',
        ];
    }
}
