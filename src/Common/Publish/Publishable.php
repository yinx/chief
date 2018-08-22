<?php

namespace Thinktomorrow\Chief\Common\Publish;

use Illuminate\Support\Carbon;


trait Publishable
{
    public function isPublished()
    {
        return $this->published_at != null && Carbon::now()->gt($this->published_at);
    }

    public function isDraft()
    {
        return $this->published_at == null || Carbon::now()->lt($this->published_at) ;
    }

    public function scopePublished($query)
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->whereDate('published_at',  '<',  Carbon::now());
    }

    public function scopeDrafted($query)
    {
        $query->whereDate('published_at',  '>',  Carbon::now())->orWhere('published_at', null);
    }

    public function publish()
    {
        $this->published_at = Carbon::now();
        $this->save();
    }

    public function draft()
    {
        $this->published_at = null;
        $this->save();
    }

    public static function getAllPublished()
    {
        return self::published()->get();
    }

    public function scopeSortedByPublished($query)
    {
        return $query->orderBy('published_at', 'ASC');
    }
}
