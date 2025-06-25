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
        Schema::create('company_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger('id_user_manager')->nullable();
            $table->text('desc')->nullable();
            $table->unsignedInteger('province')->nullable();
            $table->unsignedInteger('district')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
        
            // Khóa ngoại
            $table->foreign('id_company')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('id_user_manager')->references('id')->on('accounts')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_company_branches');
    }
};
