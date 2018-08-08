<?php

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Database\Eloquent\Model;

class FormEntry extends Model  {

    public $table = "form_entries";
    protected $casts = [
        'fields' => 'array',
    ];
}