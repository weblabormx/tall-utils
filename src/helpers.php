<?php

if (!function_exists('notify')) {
    function notify(string $title, string $description = "", string $type = "success")
    {
        $notifications = session()->get('wireui:notifications', []);
        $notifications[] = [
            "title" => $title,
            "description" => $description,
            "icon" => $type
        ];

        session()->flash('wireui:notifications', $notifications);
    }
}

if (!function_exists('dialog')) {
    function dialog(array $options = [])
    {
        session()->flash('wireui:dialog', $options);
    }
}
