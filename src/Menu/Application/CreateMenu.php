<?php

namespace Thinktomorrow\Chief\Menu\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Common\Collections\Collections;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class CreateMenu
{
    use TranslatableCommand;

    public function handle(MenuRequest $request): MenuItem
    {
        try {
            DB::beginTransaction();

            $menu = MenuItem::create();
            $menu->type = $request->get('type', null);
            $menu->parent_id = ($request->get('allow_parent') && $request->get('parent_id')) ? $request->get('parent_id') : null;
            $menu->page_id = ($page_id = $request->get('page_id')) ? $this->getPage($request->get('page_id'))->id : null;
            $menu->save();

            $this->saveTranslations($request->get('trans'), $menu, [
                'label', 'url'
            ]);

            DB::commit();

            return $menu->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getPage($flat_reference)
    {
        return FlatReferenceCollection::fromFlatReferences([$flat_reference])->first();
    }
}
