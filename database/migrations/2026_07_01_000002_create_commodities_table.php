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
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('cooperatives')->cascadeOnDelete();
            $table->string('name');
            $table->string('category')->default('Beras'); // Beras, Telur, Sayur
            $table->integer('current_capacity');
            $table->string('unit')->default('kg');
            $table->decimal('base_price_b2b', 15, 2);
            $table->string('grade')->default('Grade A Premium');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
