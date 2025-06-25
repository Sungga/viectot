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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('type');
            $table->string('status');
            $table->foreignId('id_user')->constrained('accounts')->onDelete('cascade'); // Khóa ngoại liên kết với bảng accounts
            $table->foreignId('id_branch')->nullable()->constrained('company_branches')->onDelete('cascade');
            $table->foreignId('id_post')->nullable()->constrained('posts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
