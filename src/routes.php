<?php

Route::group(array(
    "prefix" => ADMIN,
    "middleware" => ["web", "auth", "can:roles.manage"],
        ), function($route) {
    $route->group(array("prefix" => "roles"), function($route) {
        $route->any('/', array("as" => "admin.roles.show", "uses" => "Dot\Roles\Controllers\RolesController@index"));
        $route->any('/create', array("as" => "admin.roles.create", "uses" => "Dot\Roles\Controllers\RolesController@create"));
        $route->any('/{id}/edit', array("as" => "admin.roles.edit", "uses" => "Dot\Roles\Controllers\RolesController@edit"));
        $route->any('/delete', array("as" => "admin.roles.delete", "uses" => "Dot\Roles\Controllers\RolesController@delete"));
    });
});
