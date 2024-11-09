<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIconCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icon_categories', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('productTitleId');
            $table->string('productTitleNameVi');
            $table->string('productTitleNameEn')->nullable();
            $table->string('arrayId')->nullable();
            $table->string('isDeleted')->default('0');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
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
        Schema::dropIfExists('icon_categories');
    }
}
