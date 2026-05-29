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
        Schema::dropIfExists('premium_slots');

        Schema::create('premium_slots', function (Blueprint $table) {
            $table->id();
            $table->enum('zone', ['A', 'B']);
            $table->tinyInteger('position');
            $table->integer('base_price_cents');
            $table->tinyInteger('discount_pct')->nullable();
            $table->timestamp('discount_until')->nullable();
            $table->timestamp('empty_since')->nullable();
            $table->timestamps();

            $table->unique(['zone', 'position']); // ein Slot pro Zone+Position
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_slots');

        // alte Buchungs-Struktur wiederherstellen (Rollback-Sicherheit)
        Schema::create('premium_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained()->cascadeOnDelete();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->integer('price_cents');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }
};
