<?php

use Illuminate\Database\Migrations\Migration;

class CreateRolePermissions extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("roles_permissions", function ($table) {
            $table->integer('role_id')->index();
            $table->string('permission')->index();
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles_permissions');
    }
}
