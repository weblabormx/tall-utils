<?php

if (!function_exists('notify')) {
    /**
     * Queues a notification to show on the next request
     *
     * @param string $title
     * @param string $description
     * @param string $type
     * @return array[] The array of notifications on queue
     * 
     * @see \WireUi\Actions\Actionable
     * @see https://livewire-wireui.com/docs/notifications
     */
    function notify(string $title, string $description = "", string $type = "success"): array
    {
        $notifications = session()->get('wireui:notifications', []);
        $notifications[] = [
            "title" => $title,
            "description" => $description,
            "icon" => $type
        ];

        session()->flash('wireui:notifications', $notifications);

        return $notifications;
    }
}

if (!function_exists('dialog')) {
    /**
     * Queues a dialog to be shown on the next request
     *
     * @param array $options
     * @return void
     * 
     * @see https://livewire-wireui.com/docs/dialogs
     */
    function dialog(array $options = [])
    {
        session()->flash('wireui:dialog', $options);
    }
}

if (!function_exists('enum')) {
    function enum(string $type, mixed $case = null): string|BackedEnum
    {
        $type = trim($type, "\\");

        if (!str_ends_with($type, 'Enum')) {
            $type .= 'Enum';
        }

        if (!str_starts_with($type, 'App\\Enums\\')) {
            $type = "App\\Enums\\{$type}";
        }

        throw_unless(enum_exists($type), new RuntimeException("Enum of type $type doesn't exists"));

        if (is_null($case)) {
            return $type;
        }

        return $type::from($case);
    }
}
