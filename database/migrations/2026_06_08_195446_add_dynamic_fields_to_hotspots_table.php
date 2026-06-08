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
        Schema::table('hotspots', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->after('type');
            $table->boolean('is_active')->default(false)->after('criteria');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('is_active');
            $table->index('is_active'); // Scheduler und Catalog-Query filtern immer hierüber
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotspots', function (Blueprint $table) {
            //
        });
    }
};
