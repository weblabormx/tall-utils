<?php

namespace WeblaborMx\TallUtils\Models;

use Illuminate\Support\Str;

/**
 * @example "../../examples/LivewireComponent.php.example"  Example of how to implement on a Livewire Component
 */
trait WithFilters
{
    public function scopeFilterByArray($query, $array)
    {
        return $query->when(isset($array) && count($array) > 0, function ($query) use ($array) {
            $filters = collect($array)->whereNotNull()->whereNotEmpty();
            foreach ($filters as $key => $filter) {
                if (Str::startsWith($key, 'in_')) {
                    if (empty($filter) || !is_array($filter)) {
                        continue;
                    }
                    $key = substr($key, 3);
                    $query->whereIn($key, $filter);
                } else if (Str::startsWith($key, 'scope_')) {
                    $key = substr($key, 6);
                    $query->$key($filter);
                } else if (Str::startsWith($key, 'range_')) {
                    $key = substr($key, 6);
                    $dates = explode(' to ', $filter);
                    $query->whereBetween($key, $dates);
                } else if (is_string($filter) && $filter == 'null') {
                    $query->whereNull($key);
                } else if ((Str::contains($key, ['_id', 'is_', 'status']) || $key == 'id' || Str::startsWith($key, 'id')) && is_string($filter)) {
                    $query->where($key, $filter);
                } else if (is_string($filter)) {
                    $query->where($key, 'like', '%' . $filter . '%');
                }
            }

            return $query;
        });
    }
}
