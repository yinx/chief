<?php 

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ChiefForm extends Model {

    public  $table = 'forms';

    private $flows = [];

    public function __construct(array $attributes = [])
    {
        $this->flows = array_merge($this->flows, array_values(static::flow()));

        parent::__construct($attributes);
    }

    public static function guessModel(Request $request)
    {
        $type = $request->get('formtype');

        if(!$type){
            throw new \DomainException('No form type can not be empty/NULL');
        }

        $class = new $type;
        
        if(!$class){
            throw new \DomainException('No form type could be determined for ' . $type);
        }
        
        return $class;
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
            $class = 'Thinktomorrow\Chief\Flows\\'.ucfirst($flow).'FormFlow';
            (new $class)->run($this, $request);
        }
    }
}