<?php

namespace WeblaborMx\TallUtils\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TallUtilsServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once __DIR__ . '/../helpers.php';
    }
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'tall-utils');

        Blade::directive('wireUiScripts', static function (?string $attributes = '') {
            if (!$attributes) {
                $attributes = '[]';
            }

            return "{!! WireUi::directives()->scripts(attributes: $attributes) !!} {!! view('tall-utils::wireui-extra') !!}";
        });
    }
}
