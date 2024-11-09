<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('product_units', function (Blueprint $table) {
        $table->id();
        $table->string('unit_code');
        
        // Sử dụng `unsignedInteger` cho `product_id` để tương thích với `int` trong bảng `products`
        $table->unsignedInteger('product_id');
        
        $table->decimal('price', 15, 2)->default(0);
        $table->decimal('price_sale', 15, 2)->nullable();
        $table->enum('level', [1, 2, 3])->default(3); // Level thấp nhất là 3
        $table->decimal('exchangerate', 15, 2)->default(1); // Tỉ giá
        $table->timestamps();

        // Foreign keys
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        $table->foreign('unit_code')->references('unit_code')->on('units')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_units');
    }
}

