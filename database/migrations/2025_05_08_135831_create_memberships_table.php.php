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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tier'); // bronze, silver, gold, platinum
            $table->decimal('price', 10, 2);
            $table->integer('duration_days'); // validity period
            $table->integer('happi_coins_reward'); // coins awarded upon purchase
            $table->text('description');
            $table->text('benefits');
            $table->boolean('is_active')->default(true);
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
