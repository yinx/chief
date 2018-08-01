<?php

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

 
class ValidationData{

    private $rules      = [];
    private $messages   = [];
    private $attributes = [];

    public function __construct($rules, $messages, $attributes)
    {
        $this->rules      = $rules;
        $this->messages   = $messages;
        $this->attributes = $attributes;
    }

    public function validate(Request $request)
    {
        Validator::make($request->all(), $this->rules, $this->messages, $this->attributes)->validate();
    }

}
