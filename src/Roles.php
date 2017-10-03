<?php

namespace Dot\Roles;

use Auth;
use Navigation;
use URL;

class Roles extends \Dot\Platform\Plugin
{

    function boot()
    {

        parent::boot();

        Navigation::menu("sidebar", function ($menu) {
            if (Auth::user()->hasRole('superadmin')) {
                $menu->item('users.permissions', trans("admin::common.permissions"), URL::to(ADMIN . '/roles'));
            }
        });
    }
}
