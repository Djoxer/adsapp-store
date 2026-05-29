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
        Schema::create('hotspots', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['saisonal','thematisch','dynamisch','tageszeit','lokal','event']);
            $table->string('slug')->unique();
            $table->string('name');                    // "Sommer Outdoor"
            $table->string('subtitle')->nullable();    // kurze Beschreibung
            $table->text('description')->nullable();
            $table->string('hero_image')->nullable();  // Pfad/URL Hero-Bild
            $table->string('icon', 8)->nullable();     // Emoji-Icon (🌷, ☕, etc.)
            $table->timestamp('opens_at')->nullable(); // null = dauerhaft offen (thematisch)
            $table->timestamp('closes_at')->nullable();// null = kein Enddatum
            $table->json('criteria')->nullable();      // v2: Auto-Trigger-Bedingungen
            $table->timestamps();

            $table->index(['opens_at', 'closes_at']);  // für Active/Coming/Archiv-Queries
        });

        Schema::create('hotspot_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotspot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ad_id')->constrained()->cascadeOnDelete();
            $table->timestamp('added_at')->useCurrent();

            $table->unique(['hotspot_id', 'ad_id']); // kein Doppel-Mapping
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_ads');
        Schema::dropIfExists('hotspots');
    }
};
