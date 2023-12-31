<?php

namespace WeblaborMx\TallUtils\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Traversable;

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
                if (is_string($value)) {
                    $value = explode(',', $value);
                }

                if ($value instanceof Traversable) {
                    $value = [...$value];
                }

                if (is_array($value)) {
                    return $this->parseArray($value);
                }

                return collect();
            }

            public function set($model, $key, $value, $attributes)
            {
                if (
                    is_null($value) ||
                    is_string($value)
                ) {
                    return $value;
                }

                if (is_scalar($value)) {
                    return strval($value);
                }

                if ($value instanceof Traversable) {
                    $value = [...$value];
                }

                if (is_array($value)) {
                    return implode(',', $value);
                }

                throw new InvalidArgumentException('Value type can\'t be casted to CommaList', 1);
            }

            private function parseArray(array $array): Collection
            {
                return collect($array)
                    ->filter(fn ($v) => is_scalar($v))
                    ->map(fn ($v) => trim(strval($v)))
                    ->filter(fn ($v) => $v !== '');
            }
        };
    }
}
