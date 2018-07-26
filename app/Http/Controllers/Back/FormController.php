<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class FormController extends Controller{

    
    public function store(Request $request)
    {
        $model = ChiefForm::guessModel($request);
        // Form model ophalen
        $model->validation($request);

        $model->handleFlow($request);

    }
}
