<?php

namespace WeblaborMx\TallUtils\Models;

use Spatie\Sluggable\HasSlug as HasSlugTrait;
use Spatie\Sluggable\SlugOptions;

/**
 * Adds HasSlug with an opinionated configuration, saves on slug column and generates from $slugField;
 */
trait HasSlug
{
    use HasSlugTrait;

    public $slugField = 'title';
    public $slugTo = 'slug';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->slugField)
            ->saveSlugsTo($this->slugTo);
    }
}
