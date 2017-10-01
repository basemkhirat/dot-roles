<?php

namespace Dot\Roles\Controllers;

use Action;
use Auth;
use Dot\Platform\Controller;
use Dot\Platform\Plugin;
use Dot\Roles\Models\Role;
use Module;
use Redirect;
use Request;
use View;

class RolesController extends Controller
{

    public $data = array();


    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            return Auth::user()->hasRole("superadmin") ? $next($request) : Dot::forbidden();
        });
    }

    public function index()
    {

        if (Request::isMethod("post")) {
            if (Request::has("action")) {
                switch (Request::get("action")) {
                    case "delete":
                        return $this->delete();
                }
            }
        }

        $query = Role::orderBy("id", "ASC");

        if (Request::has("q")) {
            $query->search(Request::get("q"));
        }

        if (Request::has("per_page")) {
            $this->data["per_page"] = $per_page = Request::get("per_page");
        } else {
            $this->data["per_page"] = $per_page = 20;
        }

        $this->data["roles"] = $query->paginate($per_page);

        return View::make("roles::show", $this->data);
    }

    public function create()
    {

        if (Request::isMethod("post")) {

            $role = new Role();

            $role->name = Request::get("name");

            // fire saving action
            Action::fire("role.saving", $role);

            if (!$role->validate()) {
                return Redirect::back()->withErrors($role->errors())->withInput(Request::all());
            }

            $role->save();

            $role->savePermissions();

            // fire saved action
            Action::fire("role.saved", $role);

            return Redirect::route("admin.roles.edit", array("id" => $role->id))->with("message", trans("roles::roles.role_created"));
        }

        $this->data["role"] = false;
        $this->data["plugins"] = Plugin::all();
        return View::make("roles::edit", $this->data);
    }

    public function edit($id)
    {

        $role = Role::findOrFail($id);

        if (Request::isMethod("post")) {

            $role->name = Request::get("name");

            // fire saving action
            Action::fire("role.saving", $role);

            if (!$role->validate()) {
                return Redirect::back()->withErrors($role->errors())->withInput(Request::all());
            }

            $role->save();

            $role->savePermissions();

            // fire saved action
            Action::fire("role.saved", $role);

            return Redirect::back()->with("message", trans("roles::roles.role_updated"));
        }

        $this->data["role"] = $role;
        $this->data["role_permissions"] = $role->permissions->pluck("permission")->toArray();

        $this->data["plugins"] = Plugin::all();

        return View::make("roles::edit", $this->data);
    }


    public function delete()
    {
        $ids = Request::get("id");
        if (!is_array($ids)) {
            $ids = array($ids);
        }

        foreach ($ids as $ID) {
            $role = Role::findOrFail($ID);

            // fire deleting action
            Action::fire("role.saving", $role);

            $role->delete();

            // fire deleted action
            Action::fire("role.deleted", $role);
        }
        return Redirect::back()->with("message", trans("roles::roles.role_deleted"));
    }


}
