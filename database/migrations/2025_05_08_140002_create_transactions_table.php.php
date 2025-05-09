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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('transaction_type'); // membership_purchase, insurance_purchase, etc.
            $table->string('reference_id')->nullable(); // related entity id
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('payment_status'); // pending, completed, failed
            $table->string('transaction_id')->nullable(); // external payment gateway transaction id
            $table->json('transaction_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
