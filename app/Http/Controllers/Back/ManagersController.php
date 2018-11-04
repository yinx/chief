<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Application\DeleteManager;
use Thinktomorrow\Chief\Management\Application\StoreManager;
use Thinktomorrow\Chief\Management\Application\UpdateManager;
use Thinktomorrow\Chief\Management\Exceptions\DeleteAborted;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\NotAllowedManagerRoute;

class ManagersController extends Controller
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function index(string $key)
    {
        $manager = $this->managers->findByKey($key);

        if( ! $manager->can('index')){ NotAllowedManagerRoute::index($manager); }

        $managers = $manager->findAllManaged();

        return view('chief::back.managers.index',[
            'modelManager' => $manager,
            'managers' => $managers,
        ]);
    }

    public function create(string $key)
    {
        $manager = $this->managers->findByKey($key);

        if( ! $manager->can('create')){ NotAllowedManagerRoute::create($manager); }

        return view('chief::back.managers.create',[
            'manager' => $manager,
        ]);
    }

    public function store(string $key, Request $request)
    {
        $modelManager = $this->managers->findByKey($key);

        $manager = app(Storemanager::class)->handle($modelManager, $request);

        return redirect()->to($manager->route('edit'))
                         ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->modelDetails()->title . '" is toegevoegd');
    }

    public function edit(string $key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        if( ! $manager->can('edit')){ NotAllowedManagerRoute::edit($manager); }

        return view('chief::back.managers.edit',[
            'manager' => $manager,
        ]);
    }

    public function update(string $key, $id, Request $request)
    {
        $manager = $this->managers->findByKey($key, $id);

        app(Updatemanager::class)->handle($manager, $request);

        return redirect()->to($manager->route('edit'))
                         ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->modelDetails()->title . '" werd aangepast');
    }

    public function delete(string $key, $id, Request $request)
    {
        $manager = $this->managers->findByKey($key, $id);

        try{
            app(DeleteManager::class)->handle($manager, $request);
        }
        catch(DeleteAborted $e){
            return redirect()->back()->with('messages.warning', $manager->managerDetails()->singular . ' is niet verwijderd.');
        }

        return redirect()->to($manager->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $manager->modelDetails()->title . '" is verwijderd.');

    }
}
