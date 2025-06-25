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
        Schema::create('search_suggestions', function (Blueprint $table) {
            $table->id(); // id tự tăng
            $table->unsignedBigInteger('category_id'); // ID ngành nghề
            $table->string('keyword'); // Gợi ý tìm kiếm
            $table->timestamps(); // created_at và updated_at

            // $table->index('keyword'); // Tối ưu tìm kiếm theo từ khóa

            // Nếu có bảng ngành nghề riêng (ví dụ: job_categories), có thể thêm ràng buộc:
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_suggestions');
    }
};
