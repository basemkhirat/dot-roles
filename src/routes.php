<?php

Route::group(array(
    "prefix" => ADMIN,
    "middleware" => ["web", "auth"],
        ), function($route) {
    $route->group(array("prefix" => "roles"), function($route) {
        $route->any('/', array("as" => "admin.roles.show", "uses" => "RolesController@index"));
        $route->any('/create', array("as" => "admin.roles.create", "uses" => "RolesController@create"));
        $route->any('/{id}/edit', array("as" => "admin.roles.edit", "uses" => "RolesController@edit"));
        $route->any('/delete', array("as" => "admin.roles.delete", "uses" => "RolesController@delete"));
    });
});
