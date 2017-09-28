<?php

namespace Dot\Roles\Models;

use Request;
use Dot\Model;
use Dot\Roles\Models\RolePermission;

class Role extends Model {

    protected $module = "roles";

    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $searchable = ["name"];

    protected $creatingRules = [
        "name" => "required|unique:roles"
    ];

    protected $updatingRules = [
        "name" => "required|unique:roles,name,[id],id"
    ];

    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }

    public function savePermissions()
    {
        RolePermission::where("role_id", $this->id)->delete();
        if ($permissions = Request::get("permissions")) {
            foreach ($permissions as $permission) {
                RolePermission::insert(array(
                    "role_id" => $this->id,
                    "permission" => $permission
                ));
            }
        }
    }

    public function scopeSuperAdmin($query){
        $query->where("name", "superadmin");
    }

}
