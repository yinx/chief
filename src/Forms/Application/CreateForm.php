<?php

namespace Thinktomorrow\Chief\Forms\Application;

use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Common\Audit\Audit;
use Thinktomorrow\Chief\Forms\ChiefForm;
use Thinktomorrow\Chief\Forms\FormEntry;

class CreateForm
{
    use TranslatableCommand;

    public function handle(array $data): ChiefForm
    {
        try {
            DB::beginTransaction();

            $class = $data['formtype'];
            unset($data['formtype']);
            unset($data['_token']);

            $form       = new ChiefForm();
            $form->type = $class;
            $form->save();


            // create entries.
            $entry          = new FormEntry;
            $entry->fields  = json_encode($data);
            $entry->form_id = $form->id;
            $entry->save();

            Audit::activity()
                ->performedOn($form)
                ->log('created');

            DB::commit();

            return $form->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
