<?php

namespace Dot\Roles;

use Auth;
use Navigation;
use URL;

class Roles extends \Dot\Platform\Plugin
{

    protected $route_middlewares = [
        'role' => \Dot\Roles\Middlewares\RoleMiddleware::class,
        'permission' => \Dot\Roles\Middlewares\PermissionMiddleware::class
    ];

    function boot()
    {

        parent::boot();

        // Roles for superadmins only

        $this->gate->define("roles.manage", function($user){
            return $user->hasRole("superadmin");
        });

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->hasRole('superadmin')) {
                $menu->item('permissions', trans("admin::common.permissions"), URL::to(ADMIN . '/roles'))->icon("fa-unlock-alt")->order(17);
            }

        });
    }
}
