<?php

namespace Thinktomorrow\Chief\Modules\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class CreateModule
{
    use TranslatableCommand;

    public function handle(string $collection, string $slug, $page_id = null): Module
    {
        try {
            DB::beginTransaction();

            $module = Module::create(['collection' => $collection, 'slug' => $slug, 'page_id' => $page_id]);

            DB::commit();

            return $module->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
