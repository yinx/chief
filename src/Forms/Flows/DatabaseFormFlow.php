<?php

namespace Thinktomorrow\Chief\Flows;

use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;


class DatabaseFormFlow extends Flow{
    
    public static function run(ChiefForm $model, Request $request) {
        self::checkRequiredMethods($model);

        $fields = $model->customFields();
        dd($fields);
        // SAVE DATA TO DB
        
    }

    protected static function requiredMethods()
    {
        return [];
    }
}