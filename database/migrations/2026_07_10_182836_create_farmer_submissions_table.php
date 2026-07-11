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
        Schema::create('farmer_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('farmer_name');
            $table->string('phone_number')->nullable();
            $table->string('commodity_type');
            $table->integer('weight_kg');
            $table->string('photo_path')->nullable();
            $table->string('ai_grade')->nullable();
            $table->integer('ai_confidence')->nullable();
            $table->integer('estimated_price')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_submissions');
    }
};
