<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIconApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icon_approves', function (Blueprint $table) {
            $table->id();
            $table->string('product_type');
            $table->string('product_id');
            $table->string('approved_type')->nullable(); // create, update, delete
            $table->string('requested_by')->nullable();
            $table->string('requested_at')->nullable();
            $table->string('checked_by')->nullable();
            $table->string('checked_at')->nullable();
            $table->string('approved_status')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('deleted_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('icon_approves');
    }
}
