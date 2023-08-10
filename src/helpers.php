<?php

if (!function_exists('notify')) {
    function notify(string $title, string $description = "", string $type = "success"): ?\WireUi\Actions\Notification
    {
        if (!\Livewire\Livewire::isLivewireRequest()) {
            return null;
        }

        $request = new \Livewire\Request(request()->all());

        $notification = new \WireUi\Actions\Notification(\Livewire::getInstance($request->name(), $request->id()));

        return $notification->send([
            "title" => $title,
            "description" => $description,
            "icon" => $type
        ]);
    }
}

if (!function_exists('dialog')) {
    function dialog(array $options = []): \WireUi\Actions\Dialog
    {
        $dialog = new \WireUi\Actions\Dialog();

        return $dialog->show($options);
    }
}
