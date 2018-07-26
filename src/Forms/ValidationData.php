<?php

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Support\Facades\Validator;

 
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

    public function validate()
    {
        Validator::make($data, $this->rules, $this->messages, $this->attributes)->validate();
    }

}
