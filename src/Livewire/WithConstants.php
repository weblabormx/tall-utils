<?php

namespace WeblaborMx\TallUtils\Livewire;

use Exception;

/**
 * Adds an array of `$constants` to protect 
 * modifications of certain properties after mount
 */
trait WithConstants
{
    /**
     * An array of properties names to protect
     *
     * @var array
     */
    public $constants = [];

    public function updatingWithConstants($name, $value)
    {
        if (!in_array($name, $this->constants)) {
            return;
        }

        abort_if($name === 'constants', 400);

        throw_unless(
            $value === $this?->{$name},
            new Exception("Can't update '{$name}' constant property", 1)
        );
    }
}
