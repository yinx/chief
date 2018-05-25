<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Publishable;

interface PreviewableContract
{
    public function previewUrl(): string;

    public function previewIndexUrl(): string;
}