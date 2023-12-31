<?php

namespace WeblaborMx\TallUtils\Classes;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use JsonSerializable;
use Livewire\Wireable;
use Stringable;
use Traversable;

class CommaList implements
    Stringable,
    JsonSerializable,
    Jsonable,
    ArrayAccess,
    Wireable
{
    /**
     * The underlying array.
     */
    protected Collection $value;

    public function __construct(Traversable|string|null $value)
    {
        $this->setValue($value);
    }

    public function setValue(Traversable|string|null $value): void
    {
        if (is_null($value)) {
            $this->value = collect();
            return;
        }

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        if ($value instanceof Traversable) {
            $value = [...$value];
        }

        $this->value = $this->parseArray($value);
    }

    /*
     * Livewire
     */

    public static function fromLivewire($value): static
    {
        return new static($value);
    }

    public function toLivewire(): string
    {
        return $this->toString();
    }

    /*
     * Castings
     */

    public function toString(): string
    {
        return (string) $this;
    }

    public function toJson($options = 0)
    {
        return json_encode($this, $options);
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->value->implode(',');
    }

    /*
     * Array Access
     */

    public function offsetExists(mixed $offset): bool
    {
        return $this->value->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->value->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->value[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->value[$offset]);
    }


    /*
     * Internal
     */

    protected function parseArray(array $array): Collection
    {
        return collect($array)
            ->filter(fn ($v) => is_scalar($v))
            ->map(fn ($v) => trim(strval($v)))
            ->filter(fn ($v) => $v !== '')
            ->values();
    }
}
