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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // ID user
            $table->string('username')->unique()->nullable();
            
            $table->string('password')->nullable(); // Mật khẩu đã mã hóa
            $table->string('email')->unique()->nullable(); // Email 
            $table->tinyInteger('role')->default(3)->unsigned(); // Thêm trường role (0 - 9)
            $table->string('status')->nullable(); 
            // tinyInteger('role')->default(0)->comment('0: user, 1: employer, ...');

            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
