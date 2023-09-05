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
