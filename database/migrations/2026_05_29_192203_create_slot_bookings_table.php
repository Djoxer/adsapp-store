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
        Schema::create('slot_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('premium_slot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ad_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('duration_days');
            $table->integer('total_cents');
            $table->enum('status', ['pending','approved','rejected','live','expired'])->default('pending');
            $table->tinyInteger('queue_position')->default(1);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamps();

            $table->index(['premium_slot_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_bookings');
    }
};
