<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIconManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icon_management', function (Blueprint $table) {
            $table->id();
            $table->string('iconUrl');
            $table->string('productNameEn')->nullable();
            $table->string('productNameVi')->nullable();
            $table->string('category');
            $table->string('descriptionVi')->nullable();
            $table->string('descriptionEn')->nullable();
            $table->string('platform');
            $table->string('dataActionStaging');
            $table->string('dataActionProduction');
            $table->boolean('is_filterable');
            $table->json('show_position');
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
        Schema::dropIfExists('icon_management');
    }
}
