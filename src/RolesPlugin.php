<?php

class RolesPlugin extends Plugin
{

    /**
     * @return array
     */
    function info()
    {

        return [
            "name" => "roles",
            "version" => "1.0",
        ];

    }

    function boot()
    {

        Navigation::menu("sidebar", function ($menu) {
            if (Auth::user()->hasRole('superadmin')) {
                $menu->item('users.permissions', trans("admin::common.permissions"), URL::to(ADMIN . '/roles'));
            }
        });

        include __DIR__ . "/routes.php";

    }
}