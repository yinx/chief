<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;

class FormController extends Controller{

    public function store(Request $request)
    {
        $model = ChiefForm::guessModel($request);

        // Form model ophalen
        $model->validation()->validate($request);

        $model->handleFlow($request);

        return redirect()->back();
    }
}
