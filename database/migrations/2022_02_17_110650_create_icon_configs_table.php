<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIconConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icon_configs', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('productConfigId')->nullable();
            $table->string('titleVi')->nullable();
            $table->string('titleEn')->nullable();
            $table->string('name')->nullable();
            $table->enum('type', ['PRODUCT', 'ITEMS', 'SEARCH'])->nullable();
            $table->string('iconsPerRow')->nullable();
            $table->string('rowOnPage')->nullable();
            $table->string('arrayId')->nullable();
            $table->string('isDeleted')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('deleted_by')->nullable();
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
        Schema::dropIfExists('icon_configs');
    }
}
