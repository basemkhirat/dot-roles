<?php

namespace Dot\Roles\Controllers;

use Action;
use Auth;
use Dot\Platform\Controller;
use Dot\Platform\Facades\Plugin;
use Dot\Roles\Models\Role;
use Module;
use Redirect;
use Request;
use View;

/*
 * Class RolesController
 * @package Dot\Roles\Controllers
 */
class RolesController extends Controller
{

    /*
     * View payload
     * @var array
     */
    public $data = [];


    /*
     * Show all Roles
     * @return mixed
     */
    public function index()
    {

        if (Request::isMethod("post")) {
            if (Request::filled("action")) {
                switch (Request::get("action")) {
                    case "delete":
                        return $this->delete();
                }
            }
        }

        $query = Role::orderBy("id", "ASC");

        if (Request::filled("q")) {
            $query->search(Request::get("q"));
        }

        if (Request::filled("per_page")) {
            $this->data["per_page"] = $per_page = Request::get("per_page");
        } else {
            $this->data["per_page"] = $per_page = 20;
        }

        $this->data["roles"] = $query->paginate($per_page);

        return View::make("roles::show", $this->data);
    }

    /*
     * Delete role by id
     * @return mixed
     */
    public function delete()
    {
        $ids = Request::get("id");

        $ids = is_array($ids) ? $ids : [$ids];

        foreach ($ids as $id) {

            $role = Role::findOrFail($id);

            // Fire deleting action

            Action::fire("role.saving", $role);

            $role->delete();

            // Fire deleted action

            Action::fire("role.deleted", $role);
        }

        return Redirect::back()->with("message", trans("roles::roles.role_deleted"));
    }

    /*
     * Create a new role
     * @return mixed
     */
    public function create()
    {

        if (Request::isMethod("post")) {

            $role = new Role();

            $role->name = Request::get("name");

            // Fire saving action

            Action::fire("role.saving", $role);

            if (!$role->validate()) {
                return Redirect::back()->withErrors($role->errors())->withInput(Request::all());
            }

            $role->save();

            $role->savePermissions();

            // Fire saved action

            Action::fire("role.saved", $role);

            return Redirect::route("admin.roles.edit", array("id" => $role->id))->with("message", trans("roles::roles.role_created"));
        }

        $this->data["role"] = false;
        $this->data["plugins"] = Plugin::all();

        return View::make("roles::edit", $this->data);
    }

    /*
     * Edit role by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $role = Role::findOrFail($id);

        if (Request::isMethod("post")) {

            $role->name = Request::get("name");

            // Fire saving action

            Action::fire("role.saving", $role);

            if (!$role->validate()) {
                return Redirect::back()->withErrors($role->errors())->withInput(Request::all());
            }

            $role->save();

            $role->savePermissions();

            // Fire saved action

            Action::fire("role.saved", $role);

            return Redirect::back()->with("message", trans("roles::roles.role_updated"));
        }

        $this->data["role"] = $role;
        $this->data["role_permissions"] = $role->permissions->pluck("permission")->toArray();
        $this->data["plugins"] = Plugin::all();

        return View::make("roles::edit", $this->data);
    }
}
