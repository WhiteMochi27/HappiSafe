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
        Schema::create('user_insurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('insurance_product_id')->constrained('insurance_products');
            $table->string('policy_number')->unique();
            $table->decimal('price_paid', 10, 2);
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->string('status'); // active, expired, cancelled
            $table->json('policy_data'); // specific details for this policy
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
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
