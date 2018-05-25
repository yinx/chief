<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Publishable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PreviewableScope implements Scope{

    public function apply(Builder $builder, Model $model)
    {
        if($this->isPreviewAllowed()) {
            $builder->orWhere('published',0);
        }
    }

    private function isPreviewAllowed()
    {
        return (request()->has('preview-mode') && auth()->guard('chief')->check());
    }
}