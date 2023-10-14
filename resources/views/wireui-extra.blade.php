@if (session('wireui:notifications'))
    <script>
        Wireui.hook('notifications:load', () => {
            const notifications = [].concat(@json(session('wireui:notifications', [])));

            if (!notifications.length) {
                return;
            }

            notifications.forEach(element => {
                window.$wireui.notify({
                    title: element?.title,
                    description: element?.description,
                    icon: element?.icon
                })
            });
        })
    </script>
@endif

@if (session('wireui:dialog'))
    <script>
        Wireui.hook('dialog:load', () => {
            setTimeout(() => {
                const dialog = @json(session('wireui:dialog'));

                window.$wireui.dialog({
                    title: dialog?.title,
                    description: dialog?.description,
                    icon: dialog?.icon
                });
            }, 150);
        });
    </script>
@endif
