<?php

namespace TypiCMS\Modules\Users\Http\Requests;

use TypiCMS\Modules\Core\Http\Requests\AbstractFormRequest;

class FormRequestEmail extends AbstractFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
        ];
    }
}
