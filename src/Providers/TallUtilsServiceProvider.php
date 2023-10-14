<?php

namespace WeblaborMx\TallUtils\Providers;

use Illuminate\Support\Facades\Blade;
use WireUi\Providers\WireUiServiceProvider;

class TallUtilsServiceProvider extends WireUiServiceProvider
{
    public function register()
    {
        parent::register();

        app()->instance(WireUiServiceProvider::class, null);
        require_once __DIR__ . '/../helpers.php';
    }
    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'tall-utils');

        Blade::directive('wireUiScripts', static function (?string $attributes = '') {
            if (!$attributes) {
                $attributes = '[]';
            }

            return "{!! WireUi::directives()->scripts(attributes: $attributes) !!} {!! view('tall-utils::wireui-extra') !!}";
        });
    }
}
