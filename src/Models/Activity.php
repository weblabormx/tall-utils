<?php

namespace WeblaborMx\TallUtils\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity as Model;

class Activity extends Model
{
    /*
     * Functions
     */

    public function onlyChanges(): Collection
    {
        if (! $this->properties instanceof Collection) {
            return new Collection();
        }

        $properties = $this->properties->toArray();
        if (! isset($properties['old']) || ! isset($properties['attributes'])) {
            return collect($properties);
        }

        foreach ($properties['attributes'] as $key => $value) {
            if (
                (isset($properties['old'][$key]) || is_null($properties['old'][$key])) && $value == $properties['old'][$key]
            ) {
                unset($properties['old'][$key]);
                unset($properties['attributes'][$key]);
            }
        }

        return collect($properties)->map(function ($item) {
            return collect($item)->map(function ($item) {
                if (is_string($item) && ! Str::contains($item, '{')) {
                    return $item;
                }
                if (is_string($item)) {
                    return json_decode($item, true);
                }

                return $item;
            });
        });
    }

    public function changesByField(): Collection
    {
        $changes = $this->onlyChanges();
        $new_return = collect();
        collect($changes['old'] ?? null)->merge($changes['attributes'])->keys()->values()->mapWithKeys(function ($item) use ($changes) {
            return [$item => [
                'old' => $changes['old'][$item] ?? null,
                'new' => $changes['attributes'][$item] ?? null,
            ]];
        })->each(function ($item, $key) use (&$new_return) {
            if (is_array($item['old']) && is_array($item['new'])) {
                $new['old'] = collect($item['old'])->mdiff($item['new'])->dot();
                $new['new'] = collect($item['new'])->mdiff($item['old'])->dot();
                foreach ($new['old'] as $key2 => $old) {
                    $new_return[$key.'.'.$key2] = [
                        'old' => $old,
                        'new' => $new['new'][$key2],
                    ];
                }
                foreach ($new['new'] as $key2 => $new_value) {
                    if (isset($new_return[$key.'.'.$key2])) {
                        continue;
                    }
                    $new_return[$key.'.'.$key2] = [
                        'old' => $new['old'][$key2],
                        'new' => $new_value,
                    ];
                }

                return;
            }
            $new_return[$key] = $item;
        });

        return $new_return;
    }

    public function getTextAttribute(): string
    {
        if ($this->description == 'updated') {
            return 'The object was updated';
        }

        return $this->description;
    }

    /**
     * Attributes
     */
    public function getChangesTextAttribute()
    {
        $changes = $this->changesByField();
        if ($changes->count() <= 0) {
            return '';
        }

        return $changes->filter(function ($item, $key) {
            return $key != 'updated_at';
        })->map(function ($item, $key) {
            return $key.' ('.($item['old'] ?? 'empty').' to '.($item['new'] ?? 'empty').')';
        })->implode(', ');
    }
}
