<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\Chief\Management\ModelManager;
use Thinktomorrow\Chief\Fields\Types\DocumentField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\ManagesPreviews;
use Thinktomorrow\Chief\Management\ManagesPublishing;
use Thinktomorrow\Chief\Management\ManagerThatPreviews;
use Thinktomorrow\Chief\Management\ManagerThatPublishes;

class PublishedManagerFake extends AbstractManager implements ModelManager, ManagerThatPublishes, ManagerThatPreviews
{
    use ManagesPublishing,
        ManagesPreviews;
        
    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title'),
            InputField::make('custom'),
            InputField::make('title_trans')->translatable(['nl', 'fr']),
            InputField::make('content_trans')->translatable(['nl', 'fr']),
            MediaField::make('avatar'),
            DocumentField::make('doc'),
        ]);
    }

    public function setCustomField(Field $field, Request $request)
    {
        $this->model->custom_column = $request->get($field->key());
    }
}
