<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAclRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('module_id')->unsigned();
            $table->boolean('view')->default(false);
            $table->boolean('create')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
            $table->softDeletes();
            $table->timestamps();
            $table->integer('status')->default(1);
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->unique(['role_id','module_id']); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('acl_roles');
    }
}
