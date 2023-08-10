<?php

namespace WeblaborMx\TallUtils\Livewire;

/**
 * Optional livewire trait to pair with `Models\WithFilters`
 * 
 * @see \WeblaborMx\TallUtils\Models\WithFilters
 */
trait WithFilters
{
    public $filters = [];
    public $filters_keys = [];

    public function mountWithFilters()
    {
        $this->filters = collect($this->filters_keys)->mapWithKeys(fn ($key) => [$key => null]);
    }
}
