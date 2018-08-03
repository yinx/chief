<?php

namespace Thinktomorrow\Chief\Forms\Flows;

use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Application\CreateForm;


class DatabaseFormFlow extends Flow{
    
    public static function run(ChiefForm $model, $request) {
        self::checkRequiredMethods($model);

        $fields = $model->customFields();
        app(CreateForm::class)->handle($request);
    }

    protected static function requiredMethods()
    {
        return [];
    }
}