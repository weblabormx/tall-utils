<?php

namespace WeblaborMx\TallUtils\Enums;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait WithSelectInput
{
    /** 
     * @return string 
     */
    public function label()
    {
        return __(Str::headline($this->name));
    }

    public static function keyValue(): array
    {
        return collect(static::cases())
            ->mapWithKeys(fn (self $enum) => [$enum->value => $enum->label()])
            ->toArray();
    }

    public static function options(array $attributes = []): Htmlable
    {
        $html = collect(static::keyValue())->map(function ($label, $value) use ($attributes) {
            $attributes = collect($attributes)->except('selected')->merge(['value' => $value])->map(fn ($v, $k) => "$k=\"{$v}\"")->join(' ');

            return "<option {$attributes}>{$label}</option>";
        })->join("\n");

        $html = new HtmlString($html);

        return $html;
    }
}
