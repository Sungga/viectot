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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề công việc

            $table->unsignedBigInteger('id_branch');
        
            $table->enum('employment_type', ['fulltime', 'parttime', 'internship', 'freelance', 'contract', 'other']);
            $table->enum('work_mode', ['onsite', 'remote', 'hybrid', 'other']);
            $table->enum('salary_type', ['negotiable', 'range', 'upto', 'fixed', 'starting_from']);
        
            $table->string('salary')->nullable(); // Dùng chung nếu chỉ có 1 mức
        
            $table->text('description')->nullable(); // Mô tả công việc
        
            $table->date('deadline')->nullable(); // Hạn nộp hồ sơ
        
            $table->enum('status', ['active', 'expired', 'lock'])->default('active');
        
            $table->enum('gender', ['any', 'male', 'female', 'other'])->default('any');
            $table->string('experience')->nullable(); // Ví dụ: "1 năm", "Không yêu cầu"
            $table->string('degree')->nullable();     // Ví dụ: "Đại học", "Cao đẳng", "Không yêu cầu"
        
            $table->integer('quantity')->nullable(); // Số lượng cần tuyển
        
            $table->integer('job_category')->nullable(); // Ví dụ: 1, 2, 3 (ID của danh mục công việc)
            $table->json('skills')->nullable(); // Lưu dạng mảng JSON, ví dụ ["PHP", "Laravel"]

            $table->foreign('id_branch')->references('id')->on('company_branches')->onDelete('cascade');
        
            $table->timestamps(); // created_at, updated_at
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
