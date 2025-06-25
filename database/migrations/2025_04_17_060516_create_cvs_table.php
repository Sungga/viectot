<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->id(); // id tự tăng
            $table->unsignedBigInteger('id_user'); // id của người dùng
            $table->string('file_name'); // tên file hoặc đường dẫn
            $table->string('name', 255); // tên cv
            $table->tinyInteger('status')->default(2); // 1 la cv chinh, 2 la cv phu
            $table->timestamps();

            // Nếu bạn có bảng users và muốn tạo liên kết foreign key:
            $table->foreign('id_user')->references('id_user')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
