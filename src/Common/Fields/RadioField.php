<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Fields;

class RadioField extends SelectField
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::RADIO), $key);
    }
}
