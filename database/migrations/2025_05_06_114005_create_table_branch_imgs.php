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
        Schema::create('table_branch_imgs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_branch');
            $table->string('img'); 
            $table->timestamps();
        
            $table->foreign('id_branch')->references('id')->on('company_branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_branch_imgs');
    }
};
