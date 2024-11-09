<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned(); // kiểu bigint cho user_id
            $table->integer('product_id')->unsigned(); // đổi thành kiểu int cho product_id
            $table->tinyInteger('rating')->unsigned(); // Điểm đánh giá, từ 1-5
            $table->text('content'); // Nội dung bình luận
            $table->timestamps(); // Tự động tạo created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
