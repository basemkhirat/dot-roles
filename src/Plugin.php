<?php

namespace Dot\Roles;

use Auth;
use Navigation;
use URL;

class Plugin extends \Dot\Platform\Plugin
{

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
