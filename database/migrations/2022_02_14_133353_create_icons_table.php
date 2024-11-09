<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('icons', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('productId')->nullable();
            $table->string('productNameVi');
            $table->string('productNameEn')->nullable();
            $table->string('iconUrl');
            $table->string('dataActionStaging');
            $table->string('dataActionProduction');
            $table->enum('actionType', ['app_to_app', 'call_api_data_action_even', 'go_to_screen', 'open_url_in_app', 'open_url_in_browser', 'open_url_in_app_with_access_token']);
            $table->string('data')->nullable();
            $table->string('content')->nullable();
            $table->string('isNew')->nullable();
            $table->string('newBeginDay')->nullable();
            $table->string('newEndDay')->nullable();
            $table->string('isDisplay')->nullable();
            $table->string('displayBeginDay')->nullable();
            $table->string('displayEndDay')->nullable();
            $table->string('decriptionVi')->nullable();
            $table->string('decriptionEn')->nullable();
            $table->string('keywords')->nullable();
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
        Schema::dropIfExists('icons');
    }
}
