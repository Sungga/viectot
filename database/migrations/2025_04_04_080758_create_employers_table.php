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
        Schema::create('employers', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('id_user')->constrained('accounts')->onDelete('cascade'); // Khóa ngoại liên kết với bảng accounts
            $table->string('name', 100)->nullable(); // Giới hạn tên tối đa 100 ký tự
            $table->string('avatar')->nullable(); // Avatar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
