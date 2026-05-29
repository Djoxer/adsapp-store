<?php

namespace Database\Seeders;

use App\Models\Hotspot;
use App\Models\Ad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HotspotSeeder extends Seeder
{
    public function run(): void
    {
        $hotspots = [
            [
                'type'        => 'saisonal',
                'name'        => 'Sommer Outdoor',
                'subtitle'    => 'Garten, Camping, Sommermode',
                'description' => 'Zeitlich begrenzter Hotspot für die warme Jahreszeit. Outdoor-Equipment, Gartenmöbel und Sommermode.',
                'icon'        => '🌞',
                'opens_at'    => now()->subDays(5),
                'closes_at'   => now()->addDays(12),
            ],
            [
                'type'        => 'thematisch',
                'name'        => 'Tech Wear',
                'subtitle'    => 'Funktionale Mode trifft Technik',
                'description' => 'Kuratierter Dauer-Hotspot für technische Bekleidung und Wearables.',
                'icon'        => '⚡',
                'opens_at'    => now()->subDays(2),
                'closes_at'   => now()->addDays(8),
            ],
            [
                'type'        => 'thematisch',
                'name'        => 'Nachhaltigkeit',
                'subtitle'    => 'Nur mit nachvollziehbarem Impact-Claim',
                'description' => 'Dauerhaft offen, kuratiert. Eintrittsschwelle hält Greenwashing draußen.',
                'icon'        => '🌱',
                'opens_at'    => null,  // dauerhaft offen
                'closes_at'   => null,
            ],
            // ── Coming Soon ──
            [
                'type'        => 'saisonal',
                'name'        => 'Frühling Mode',
                'subtitle'    => 'Leichte Stoffe, frische Farben',
                'description' => 'Startet im Frühjahr — Mode und Accessoires für die Übergangszeit.',
                'icon'        => '🌷',
                'opens_at'    => now()->addDays(17),
                'closes_at'   => now()->addDays(90),
            ],
            [
                'type'        => 'event',
                'name'        => 'Tech Hardware',
                'subtitle'    => 'Launch-Event Neuheiten',
                'description' => 'Themen-Hotspot rund um aktuelle Hardware-Releases.',
                'icon'        => '🖥️',
                'opens_at'    => now()->addDays(24),
                'closes_at'   => now()->addDays(38),
            ],
            [
                'type'        => 'tageszeit',
                'name'        => 'Audio Performance',
                'subtitle'    => 'HiFi, Studio, Live-Sound',
                'description' => 'Audio-Equipment für Enthusiasten und Profis.',
                'icon'        => '🎧',
                'opens_at'    => now()->addDays(33),
                'closes_at'   => now()->addDays(47),
            ],
            // ── Archiv ──
            [
                'type'        => 'saisonal',
                'name'        => 'Winter Gear',
                'subtitle'    => 'Ski, Snowboard, Winterkleidung',
                'description' => 'Abgelaufener Winter-Hotspot.',
                'icon'        => '❄️',
                'opens_at'    => now()->subDays(120),
                'closes_at'   => now()->subDays(28),
            ],
            [
                'type'        => 'thematisch',
                'name'        => 'Home Office',
                'subtitle'    => 'Schreibtisch, Stuhl, Setup',
                'description' => 'Abgelaufener Hotspot rund ums Arbeiten von zuhause.',
                'icon'        => '🏠',
                'opens_at'    => now()->subDays(90),
                'closes_at'   => now()->subDays(44),
            ],
        ];

        foreach ($hotspots as $data) {
            $hotspot = Hotspot::firstOrCreate(
                ['slug' => Str::slug($data['name'])],
                $data
            );

            // Aktiven Hotspots ein paar zufällige aktive Ads zuordnen
            // (nur wenn aktiv UND Ads vorhanden)
            $isActive = (is_null($data['opens_at']) || $data['opens_at'] <= now())
                && (is_null($data['closes_at']) || $data['closes_at'] >= now());

            if ($isActive && $hotspot->ads()->count() === 0) {
                $randomAds = Ad::where('status', 'active')
                    ->inRandomOrder()
                    ->limit(rand(3, 8))
                    ->pluck('id');

                $hotspot->ads()->syncWithoutDetaching($randomAds);
            }
        }
    }
}
