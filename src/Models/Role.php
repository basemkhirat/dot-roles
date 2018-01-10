<?php

namespace Dot\Roles\Models;

use Dot\Platform\Model;
use Request;

/*
 * Class Role
 * @package Dot\Roles\Models
 */
class Role extends Model
{

    /*
     * @var bool
     */
    public $timestamps = false;
    /*
     * @var string
     */
    protected $table = 'roles';
    /*
     * @var string
     */
    protected $primaryKey = 'id';
    /*
     * @var array
     */
    protected $searchable = ["name"];

    /*
     * @var array
     */
    protected $creatingRules = [
        "name" => "required|unique:roles"
    ];

    /*
     * @var array
     */
    protected $updatingRules = [
        "name" => "required|unique:roles,name,[id],id"
    ];

    /*
     * Permissions relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }


    /*
     * Save role permissions
     */
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

    /*
     * Check if superadmin
     * @param $query
     */
    public function scopeSuperAdmin($query)
    {
        $query->where("name", "superadmin");
    }

}
