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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('txn_ref')->unique();
            $table->text('order_info');
            $table->string('transaction_no')->nullable();
            $table->string('bank_code')->nullable();
            $table->integer('amount');
            $table->dateTime('pay_date')->nullable();
            $table->json('raw_data')->nullable();
            $table->string('status')->default('pending'); // pending | paid | failed | expired
            $table->timestamps();
        
            $table->foreign('id_user')->references('id_user')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
