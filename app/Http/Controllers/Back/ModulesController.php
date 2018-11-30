<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\Module;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\ModuleCreateRequest;
use Thinktomorrow\Chief\Modules\Application\UpdateModule;
use Thinktomorrow\Chief\App\Http\Requests\ModuleUpdateRequest;
use Thinktomorrow\Chief\Modules\Application\DeleteModule;

class ModulesController extends Controller
{
    public function index()
    {
        $this->authorize('view-page');

        return view('chief::back.modules.index', [
            'modelManager' => '',
            'modules' => Module::withoutPageSpecific()->get(),
        ]);
    }

    public function store(ModuleCreateRequest $request)
    {
        $manager = app(Managers::class)->findByKey($request->module_key);

        $manager->guard('edit');

        $module = app(CreateModule::class)->handle(
            $request->get('module_key'),
            $request->get('slug'),
            $request->get('page_id')
        );

        // Populate the manager with the model so we can direct the admin to the correct page.
        $manager->manage($module);

        return redirect()->to($manager->route('edit'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->modelDetails()->title . '" is toegevoegd');
    }
}
