<?php

namespace Chief\Common\Traits;

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
        $query->where('published',1);
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
}