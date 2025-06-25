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
        Schema::create('applies', function (Blueprint $table) {
            $table->id();
            $table->text('profileSummary')->nullable(); // Mô tả bản thân
            $table->unsignedBigInteger('id_cv');
            $table->unsignedBigInteger('id_candidate');
            $table->unsignedBigInteger('id_post');
            $table->string('status');

            $table->timestamps();

            $table->foreign('id_candidate')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('id_cv')->references('id')->on('cvs')->onDelete('cascade');
            $table->foreign('id_post')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
