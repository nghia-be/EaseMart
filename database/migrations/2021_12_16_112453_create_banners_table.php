<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('show_at');
            $table->string('path_1');
            $table->string('path_2')->nullable();
            $table->string('target_route')->nullable();
            $table->dateTime('show_from')->nullable();
            $table->dateTime('show_to')->nullable();
            $table->integer('priority');
            $table->bigInteger('view_count');
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
