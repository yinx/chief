<?php

namespace Thinktomorrow\Chief\Flows;

use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Notifications\FlowMail;

class Flow{
    protected static function checkRequiredMethods(ChiefForm $model)
    {
        foreach(static::requiredMethods() as $method)
        {
            if(!method_exists($model, $method))
            {
                throw new \Exception('Form needs to implement the '. $method .' method when defining the mail flow');
            }
        }
    }
}