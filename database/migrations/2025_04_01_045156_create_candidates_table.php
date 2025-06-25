<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('id_user')->constrained('accounts')->onDelete('cascade'); // Khóa ngoại liên kết với bảng accounts
            
            $table->string('name', 100)->nullable(); // Giới hạn tên tối đa 100 ký tự
            $table->string('avatar')->nullable(); // Avatar

            $table->unsignedBigInteger('province')->nullable(); // Lưu ID của tỉnh/thành phố
            $table->unsignedBigInteger('district')->nullable(); // Lưu ID của quận/huyện
            
            $table->date('birthdate')->nullable(); // Lưu ngày sinh theo kiểu DATE
            $table->enum('sex', ['nam', 'nữ', 'khác'])->nullable(); // Giới tính với giá trị cố định
            $table->string('phone', 15)->nullable(); // Số điện thoại (tối đa 15 số)
            
            $table->unsignedBigInteger('id_category')->nullable(); // Chuyển id_category về kiểu số

            $table->unsignedBigInteger('cv_limit')->default(3)->nullable(); // Giới hạn lưu trữ cv

            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('candidates');
    }
};
