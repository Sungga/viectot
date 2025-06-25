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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_candidate');
            $table->unsignedBigInteger('id_branch');
            $table->unsignedBigInteger('id_post');
            $table->text('message');
            $table->enum('sender', ['candidate', 'branch']); // ai là người gửi
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->timestamps();

            $table->foreign('id_candidate')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('id_branch')->references('id')->on('company_branches')->onDelete('cascade');
            $table->foreign('id_post')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
