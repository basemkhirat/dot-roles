<?php

Route::group([
    "prefix" => ADMIN,
    "middleware" => ["web", "auth:backend", "can:roles.manage"],
    "namespace" => "Dot\\Roles\\Controllers"
], function ($route) {
    $route->group(["prefix" => "roles"], function ($route) {
        $route->any('/', ["as" => "admin.roles.show", "uses" => "RolesController@index"]);
        $route->any('/create', ["as" => "admin.roles.create", "uses" => "RolesController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.roles.edit", "uses" => "RolesController@edit"]);
        $route->any('/delete', ["as" => "admin.roles.delete", "uses" => "RolesController@delete"]);
    });
});
