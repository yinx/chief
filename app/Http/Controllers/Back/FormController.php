<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;

class FormController extends Controller{

    public function index()
    {
        // REACTIVATE THIS WHEN PUSHING
        // $this->authorize('view-forms');

        $forms = ChiefForm::with('entries')->get();

        return view('chief::back.forms.index', compact('forms'));
    }

    public function store(Request $request)
    {
        $model = ChiefForm::guessModel($request->all());

        // Form model ophalen
        $model->validation()->validate($request->all());

        $model->handleFlow($request->all());

        return redirect()->back();
    }

    public function show($id)
    {
        $form = ChiefForm::find($id);

        return view('chief::back.forms.show', compact('form'));
    }
}
