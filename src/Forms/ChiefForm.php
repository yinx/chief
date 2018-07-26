<?php 

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Database\Eloquent\Model;

class ChiefForm extends Model {

    public    $table = 'forms';

    private $flows = [];

    public function __construct(array $attributes = [])
    {
        $this->flows = array_merge($this->flows, array_keys(static::flow()));

        parent::__construct($attributes);
    }

    public function customFields()
    {
        return [];
    }

    public function flow()
    {
        return [];
    }

    public function handleFlow($request)
    {
        foreach($this->flows as $flow)
        {
            $class = $flow.'FormFlow';
            $class->run($this, $request);
        }
    }
}