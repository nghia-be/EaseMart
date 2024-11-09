<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_module', function (Blueprint $table) {
            $table->id();
            $table->string('group_module_name');
            $table->softDeletes();
            $table->integer('status')->default(1);
            $table->timestamps();
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
        // Schema::dropIfExists('group_modules');
    }
}
