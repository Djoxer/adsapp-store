<?php

namespace Database\Seeders;

use App\Models\PremiumSlot;
use Illuminate\Database\Seeder;

class PremiumSlotSeeder extends Seeder
{
    public function run(): void
    {
        // ── Zone A: 3 Slots, einheitlicher Tagespreis (FIFO-Queue) ──
        foreach (range(1, 3) as $pos) {
            PremiumSlot::firstOrCreate(
                ['zone' => 'A', 'position' => $pos],
                ['base_price_cents' => 490]  // €4,90/Tag
            );
        }

        // ── Zone B: 4 Slots, gestaffelt (Fixed-Price, Position locked) ──
        $zoneBPrices = [990, 690, 490, 290]; // Position 1 teuerste → 4 günstigste
        foreach ($zoneBPrices as $i => $price) {
            PremiumSlot::firstOrCreate(
                ['zone' => 'B', 'position' => $i + 1],
                ['base_price_cents' => $price]
            );
        }
    }
}
