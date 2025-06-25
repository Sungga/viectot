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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('id_user_main');
            $table->text('desc')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: pending, 1: approved, 2: rejected');
            $table->timestamps();
        
            // Khóa ngoại tới bảng users
            $table->foreign('id_user_main')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_companies');
    }
};
