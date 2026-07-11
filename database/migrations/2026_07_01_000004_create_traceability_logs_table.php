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
        Schema::create('traceability_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->constrained('commodities')->cascadeOnDelete();
            $table->string('farmer_group_name');
            $table->date('harvest_date');
            $table->string('qr_code_token')->unique();
            $table->integer('food_safety_score')->default(98); // Score 0-100
            $table->string('lab_certified_by')->default('Lab Sucofindo & Dinas Ketahanan Pangan');
            $table->string('location_coordinates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traceability_logs');
    }
};
