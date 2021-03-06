<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ManagerDetails
 * @property $key
 * @property $class
 * @property $singular
 * @property $plural
 * @property $slug
 */
class ManagedModelDetails implements Arrayable
{
    /** @var array */
    private $values = [];

    public function __construct($id, string $title, string $subtitle, string $intro, array $locales)
    {
        $this->values['id'] = $id;
        $this->values['title'] = $title;
        $this->values['subtitle'] = $subtitle;
        $this->values['intro'] = $intro;
        $this->values['locales'] = $locales;
    }

    public function all(): array
    {
        return $this->values;
    }

    public function get($attribute = null)
    {
        if (array_key_exists($attribute, $this->values)) {
            return $this->values[$attribute];
        }

        return null;
    }

    public function __get($attribute)
    {
        return $this->get($attribute);
    }

    public function __toString()
    {
        return (string)$this->get('key');
    }

    public function toArray()
    {
        return $this->all();
    }
}
