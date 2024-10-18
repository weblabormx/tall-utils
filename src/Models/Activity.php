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
        $changes['old'] = collect($changes['old'] ?? [])->dot();
        $changes['attributes'] = collect($changes['attributes'] ?? [])->dot();
        return collect($changes['old'])->merge($changes['attributes'])->keys()->values()->mapWithKeys(function ($item) use ($changes) {
            return [$item => [
                'old' => $changes['old'][$item] ?? null,
                'new' => $changes['attributes'][$item] ?? null,
            ]];
        });
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
            if(is_array($item['old'])) {
                $item['old'] = json_encode($item['old']);
            }
            if(is_array($item['new'])) {
                $item['new'] = json_encode($item['new']);
            }
            return $key.' ('.($item['old'] ?? 'empty').' to '.($item['new'] ?? 'empty').')';
        })->implode(', ');
    }
}
