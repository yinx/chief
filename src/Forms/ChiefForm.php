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

    public static function guessModel($request)
    {
        $type = $request['formtype'];

        if(!$type){
            throw new \DomainException('Form type can not be empty/NULL');
        }

        if(!$class = new $type){
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
            $class = 'Thinktomorrow\Chief\Forms\Flows\\'.ucfirst($flow).'FormFlow';
            (new $class)->run($this, $request);
        }
    }

    public function entries()
    {
        return $this->hasMany(FormEntry::class, 'form_id');
    }

    public function getClassTypeAttribute()
    {
        strtok($this->type, '\\');
        strtok('\\');
        return strtok('');
    }
}