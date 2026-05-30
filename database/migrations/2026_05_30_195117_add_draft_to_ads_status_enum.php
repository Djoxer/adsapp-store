<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 'draft' zum Enum hinzufügen — Code (Stats, Toggle, Formular) rechnet bereits damit
        DB::statement("ALTER TABLE ads MODIFY COLUMN status ENUM('active','paused','archived','draft') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Vor dem Zurückrollen: bestehende 'draft'-Ads auf 'paused' heben,
        // sonst schlägt das Enum-Shrink fehl (truncation bei vorhandenen draft-Werten)
        DB::statement("UPDATE ads SET status = 'paused' WHERE status = 'draft'");
        DB::statement("ALTER TABLE ads MODIFY COLUMN status ENUM('active','paused','archived') NOT NULL DEFAULT 'active'");
    }
};
