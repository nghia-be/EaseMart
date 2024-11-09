<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAppLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_log', function (Blueprint $table) {
            $table->id();
            $table->string('type', 255);
            $table->string('action_name', 255);
            $table->string('phone', 50);
            $table->string('function_name', 255);
            $table->string('date_action', 255);
            $table->string('url', 255);
            $table->text('result');
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
        Schema::dropIfExists('app_log');
    }
}
