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
        Schema::create('alert_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reporter_name');
            $table->unsignedBigInteger('reporter_id');
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('post_id');
            $table->string('status');
            $table->timestamps();

            // Nếu có liên kết với bảng users và posts
            $table->foreign('reporter_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_admins');
    }
};
