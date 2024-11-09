<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Constraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles_old');
        });
        Schema::table('user_groups', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
        });
        Schema::table('acl_roles', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles_old');
            $table->foreign('module_id')->references('id')->on('modules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('acl_roles', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['module_id']);
        });
        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
        Schema::dropIfExists('group_module');
        Schema::dropIfExists('acl_roles');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('user_groups');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles_old');
    }
}
