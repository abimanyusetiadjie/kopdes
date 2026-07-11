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
        Schema::create('pre_order_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->constrained('commodities')->cascadeOnDelete();
            $table->enum('buyer_type', ['MBG_Unit', 'Corporate'])->default('MBG_Unit');
            $table->string('buyer_name');
            $table->integer('volume_requested');
            $table->decimal('agreed_price', 15, 2);
            $table->enum('contract_status', ['Pending', 'Active', 'Completed'])->default('Pending');
            $table->date('delivery_target_date');
            $table->string('contract_number')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_order_contracts');
    }
};
