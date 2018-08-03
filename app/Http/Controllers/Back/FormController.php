<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;

class FormController extends Controller{

    public function store(Request $request)
    {
        $model = ChiefForm::guessModel($request->all());

        // Form model ophalen
        $model->validation()->validate($request->all());

        $model->handleFlow($request->all());

        return redirect()->back();
    }
}
