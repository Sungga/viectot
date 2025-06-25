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
        Schema::create('generated_cvs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cv_id');
            $table->string('name');
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar_path')->nullable();
            $table->text('career_goal')->nullable();
            $table->string('template')->default('classic');

            // Các trường gộp dạng TEXT hoặc JSON
            $table->longText('education')->nullable();     // text hoặc json
            $table->longText('experience')->nullable();    // text hoặc json
            $table->longText('projects')->nullable();
            $table->longText('skills')->nullable();
            $table->longText('certificates')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id_user')->on('candidates')->onDelete('cascade');
            $table->foreign('cv_id')->references('id')->on('cvs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_cvs');
    }
};
