<?php

namespace WeblaborMx\TallUtils\Observers;

use Illuminate\Database\Eloquent\Model;

class ForceDefaultsObserver
{
    public function saving(Model $model)
    {
        $this->applyDefaults($model);
    }

    protected function applyDefaults(Model $model)
    {
        $class = $model->getMorphClass();
        $defaultAttributes = (new $class)->getAttributes();
        if(empty($defaultAttributes)) {
            return;
        }

        foreach ($model->getAttributes() as $key => $value) {
            if (array_key_exists($key, $defaultAttributes) && is_null($model->{$key})) {
                $model->{$key} = $defaultAttributes[$key];
            }
        }
    }
}
