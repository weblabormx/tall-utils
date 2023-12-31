<?php

namespace WeblaborMx\TallUtils\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use WeblaborMx\TallUtils\Classes\CommaList;

class AsCommaList implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return \Illuminate\Contracts\Database\Eloquent\CastsAttributes<\Illuminate\Support\Stringable, string|\Stringable>
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                return new CommaList($value);
            }

            public function set($model, $key, $value, $attributes)
            {
                return (new CommaList($value))->toString();
            }
        };
    }
}
