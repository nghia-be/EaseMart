<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('uri')->nullable();
            $table->string('icon')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->integer('status')->default(1);
            $table->bigInteger('group_module_id')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
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
        // Schema::dropIfExists('modules');
    }
}
