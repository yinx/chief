<?php

namespace Thinktomorrow\Chief\Modules;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\ModelManager;
use Thinktomorrow\Chief\Management\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Modules\Application\DeleteModule;

class ModuleManager extends AbstractManager implements ModelManager
{
    public function route($verb): ?string
    {
        /**
         * Page specific modules are expected to be found and managed in the context of a certain page.
         * Therefore the index of these modules is at the modules tab of this page model.
         */
        if($verb == 'index' && $this->model->isPageSpecific()){
            return app(Managers::class)->findByModel($this->model->page)->route('edit').'#eigen%20modules';
        }

        $routes = [
            'index'   => route('chief.back.managers.index', [$this->registration->key()]),
            'create'  => route('chief.back.managers.create', [$this->registration->key()]),
            'store'   => route('chief.back.managers.store', [$this->registration->key(), $this->model->id]),
            'edit'    => route('chief.back.managers.edit', [$this->registration->key(), $this->model->id]),
            'update'  => route('chief.back.managers.update', [$this->registration->key(), $this->model->id]),
            'delete' => route('chief.back.managers.delete', [$this->registration->key(), $this->model->id]),
            'upload' => route('managers.media.upload', [$this->registration->key(), $this->model->id])
        ];

        return $routes[$verb] ?? null;
    }

    public function can($verb): bool
    {
        $this->authorize($verb);

        return parent::can($verb);
    }

    private function authorize($verb)
    {
        $permission = 'update-page';

        if (in_array($verb, ['index','show'])) {
            $permission = 'view-page';
        } elseif (in_array($verb, ['create','store'])) {
            $permission = 'create-page';
        } elseif (in_array($verb, ['delete'])) {
            $permission = 'delete-page';
        }

        if (! auth()->guard('chief')->user()->hasPermissionTo($permission)) {
            throw NotAllowedManagerRoute::notAllowedPermission($permission);
        }
    }

    /**
     * The set of fields that should be manageable for a certain model.
     *
     * Additionally, you should:
     * 1. Make sure to setup the proper migrations and
     * 2. For a translatable field you should add this field to the $translatedAttributes property of the model as well.
     *
     * @return Fields
     */
    public function fields(): Fields
    {
        return new Fields([
            InputField::make('slug')->validation('required'),
            InputField::make('title')->translatable($this->model->availableLocales())->validation('required-fallback-locale|max:200'),
            HtmlField::make('content')->translatable($this->model->availableLocales()),
        ]);
    }

    public function saveFields(): ModelManager
    {
        // Store the morph_key upon creation
        if (! $this->model->morph_key) {
            $this->model->morph_key = $this->model->morphKey();
        }

        return parent::saveFields();
    }

    public function delete()
    {
        $this->guard('delete');

        app(DeleteModule::class)->handle($this->model->id);
    }

    public function storeRequest(Request $request): Request
    {
        $trans = [];
        foreach ($request->get('trans', []) as $locale => $translation) {
            if (is_array_empty($translation)) {
                continue;
            }

            $trans[$locale] = $translation;
        }

        return $request->merge(['trans' => $trans]);
    }

    public function updateRequest(Request $request): Request
    {
        $trans = [];
        foreach ($request->get('trans', []) as $locale => $translation) {
            if (is_array_empty($translation)) {

                // Nullify all values
                $trans[$locale] = array_map(function ($value) {
                    return null;
                }, $translation);
                continue;
            }

            $trans[$locale] = $translation;
        }

        return $request->merge(['trans' => $trans]);
    }
}
