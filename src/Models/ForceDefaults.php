<?php

namespace WeblaborMx\TallUtils\Models;

use WeblaborMx\TallUtils\Observers\ForceDefaultsObserver;

trait ForceDefaults
{
    public static function bootForceDefaults()
    {
        static::observe(ForceDefaultsObserver::class);
    }
}