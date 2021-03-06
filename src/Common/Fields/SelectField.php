<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Fields;

class SelectField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::SELECT), $key);
    }

    public function options(array $values)
    {
        $this->values['options'] = $values;

        return $this;
    }

    public function selected($values)
    {
        $this->values['selected'] = $values;

        return $this;
    }

    public function multiple($value)
    {
        $this->values['multiple'] = $value;

        return $this;
    }
}
