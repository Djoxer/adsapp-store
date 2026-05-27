<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ad;
use App\Models\Merchant;
use App\Models\Category;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        // Voraussetzung: mind. 1 Merchant und Kategorien existieren
        if (Merchant::count() === 0 || Category::count() === 0) {
            $this->command->warn('AdSeeder: Keine Merchants oder Kategorien gefunden — erst MerchantSeeder/CategorySeeder laufen lassen.');
            return;
        }

        // 25 aktive Ads — genug für alle Catalog-Zonen
        Ad::factory()->count(25)->create();

        $this->command->info('25 Test-Ads erstellt.');
    }
}
