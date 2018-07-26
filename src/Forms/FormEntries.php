<?php 

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Database\Eloquent\Model;

class FormEntries extends Model  {

    public $table = "form_entries";

    public function form()
    {
        return $this->belongsTo(ChiefForm::class, 'form_id');
    }
}