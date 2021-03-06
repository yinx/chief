<?php

namespace Thinktomorrow\Chief\Common\Publish;

trait Publishable
{
    public function isPublished()
    {
        return (!!$this->published);
    }

    public function isDraft()
    {
        return (!$this->published);
    }

    public function scopePublished($query)
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->where('published', 1);
    }

    public function scopeDrafted($query)
    {
        $query->where('published', 0);
    }

    public function publish()
    {
        $this->published = 1;
        $this->save();
    }

    public function draft()
    {
        $this->published = 0;
        $this->save();
    }

    public static function getAllPublished()
    {
        return self::published()->get();
    }

    public function scopeSortedByPublished($query)
    {
        return $query->orderBy('published', 'DESC');
    }
}
