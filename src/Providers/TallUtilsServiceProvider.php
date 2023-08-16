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

            $extra = <<<HTML
<script>
    Wireui.hook('notifications:load', () => {
        const notifications = [].concat({!! json_encode(session('wireui:notifications', [])) !!});

        notifications.forEach(element => {
            \$wireui.notify({
                title: element?.title,
                description: element?.description,
                icon: element?.icon
            })
        });
    })
</script>

<script>
    Wireui.hook('dialog:load', () => {
        setTimeout(() => {
            const dialog = {!! session('wireui:dialog') ? json_encode(session('wireui:dialog')) : 'null' !!};

            if(dialog){
                \$wireui.dialog({
                    title: dialog?.title,
                    description: dialog?.description,
                    icon: dialog?.icon
                });
            }
        }, 150);
    });
</script>
HTML;

            return "{!! WireUi::directives()->scripts(attributes: {$attributes}) !!}" . $extra;
        });
    }
}
