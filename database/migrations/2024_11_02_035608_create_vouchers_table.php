<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id(); // Tạo cột id tự động tăng
            $table->string('code')->unique(); // Mã voucher
            $table->float('discount');
            $table->date('start_date'); // Ngày bắt đầu
            $table->date('end_date'); // Ngày kết thúc
            $table->integer('usage_limit')->nullable(); // Giới hạn số lần sử dụng
            $table->integer('used_count')->default(0); // Số lần đã sử dụng
            $table->boolean('status')->default(true); // Trạng thái kích hoạt
            $table->timestamps(); // Thêm created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}

