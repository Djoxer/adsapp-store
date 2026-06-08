<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotspotSeeder extends Seeder
{
    public function run(): void
    {
        // Bestehende Kalender-Hotspots zuerst löschen (idempotent — Seeder kann
        // mehrfach laufen ohne Duplikate). Dynamische (category_id IS NOT NULL)
        // werden vom Scheduler verwaltet, nicht hier.
        DB::table('hotspots')
            ->whereNull('category_id')
            ->whereIn('type', ['saisonal', 'tageszeit', 'event'])
            ->delete();

        $now = now();

        // ══════════════════════════════════════════════
        // JAHRESZEITEN — öffnen/schließen per Datum
        // is_active wird vom Scheduler gesetzt, hier false als Default
        // ══════════════════════════════════════════════
        $seasons = [
            [
                'slug'        => 'jahreszeit-fruehling',
                'name'        => 'Frühling',
                'subtitle'    => 'Aufblühendes, Outdoor & frische Farben',
                'icon'        => '🌷',
                'type'        => 'saisonal',
                // criteria speichert Monat/Tag als MM-DD — kein Jahr, jährlich wiederkehrend
                'criteria'    => json_encode(['start' => '03-01', 'end' => '05-31']),
            ],
            [
                'slug'        => 'jahreszeit-sommer',
                'name'        => 'Sommer',
                'subtitle'    => 'Outdoor, Reise & alles unter der Sonne',
                'icon'        => '☀️',
                'type'        => 'saisonal',
                'criteria'    => json_encode(['start' => '06-01', 'end' => '08-31']),
            ],
            [
                'slug'        => 'jahreszeit-herbst',
                'name'        => 'Herbst',
                'subtitle'    => 'Gemütlichkeit, Ernte & warme Töne',
                'icon'        => '🍂',
                'type'        => 'saisonal',
                'criteria'    => json_encode(['start' => '09-01', 'end' => '11-30']),
            ],
            [
                'slug'        => 'jahreszeit-winter',
                'name'        => 'Winter',
                'subtitle'    => 'Kälte, Wärme & Schnee',
                'icon'        => '❄️',
                'type'        => 'saisonal',
                // Winter überspannt Jahreswechsel — Scheduler muss das gesondert
                // behandeln: aktiv wenn Monat >= 12 ODER Monat <= 2
                'criteria'    => json_encode(['start' => '12-01', 'end' => '02-28', 'wraps_year' => true]),
            ],
        ];

        // ══════════════════════════════════════════════
        // TAGESZEITEN — aktivieren sich täglich per Uhrzeit (UTC+1/+2 via App-Timezone)
        // criteria: start_hour / end_hour in App-Lokalzeit
        // ══════════════════════════════════════════════
        $daytimes = [
            [
                'slug'     => 'tageszeit-morgen',
                'name'     => 'Guten Morgen',
                'subtitle' => 'Der perfekte Start in den Tag',
                'icon'     => '🌅',
                'type'     => 'tageszeit',
                'criteria' => json_encode(['start_hour' => 6, 'end_hour' => 9]),
            ],
            [
                'slug'     => 'tageszeit-mittag',
                'name'     => 'Mittagspause',
                'subtitle' => 'Kurze Auszeit, interessante Entdeckungen',
                'icon'     => '☕',
                'type'     => 'tageszeit',
                'criteria' => json_encode(['start_hour' => 10, 'end_hour' => 14]),
            ],
            [
                'slug'     => 'tageszeit-nachmittag',
                'name'     => 'Nachmittag',
                'subtitle' => 'Produktiv, entspannt, neugierig',
                'icon'     => '🌤️',
                'type'     => 'tageszeit',
                'criteria' => json_encode(['start_hour' => 15, 'end_hour' => 17]),
            ],
            [
                'slug'     => 'tageszeit-abend',
                'name'     => 'Abendstunden',
                'subtitle' => 'Feierabend-Stöbern & Inspirationen',
                'icon'     => '🌆',
                'type'     => 'tageszeit',
                'criteria' => json_encode(['start_hour' => 18, 'end_hour' => 22]),
            ],
            [
                'slug'     => 'tageszeit-nacht',
                'name'     => 'Nachtschicht',
                'subtitle' => 'Für die, die noch wach sind',
                'icon'     => '🌙',
                'type'     => 'tageszeit',
                // Nacht überspannt Mitternacht: start > end → Scheduler-Sonderfall
                'criteria' => json_encode(['start_hour' => 23, 'end_hour' => 5, 'wraps_midnight' => true]),
            ],
        ];

        // ══════════════════════════════════════════════
        // FESTLICHKEITEN — feste + berechnete Daten
        // opens_at / closes_at werden vom Scheduler jährlich neu gesetzt.
        // criteria speichert die Berechnungsregel.
        // Fenster: 2 Wochen vor dem Datum, 1 Tag danach (außer Angegeben)
        // ══════════════════════════════════════════════
        $events = [
            [
                'slug'     => 'event-valentinstag',
                'name'     => 'Valentinstag',
                'subtitle' => 'Geschenke, Romantik & besondere Momente',
                'icon'     => '❤️',
                'type'     => 'event',
                'criteria' => json_encode([
                    'rule'         => 'fixed',
                    'month'        => 2,
                    'day'          => 14,
                    'days_before'  => 14,
                    'days_after'   => 1,
                ]),
            ],
            [
                'slug'     => 'event-ostern',
                'name'     => 'Ostern',
                'subtitle' => 'Frühjahrsgefühle, Geschenke & Traditionen',
                'icon'     => '🐣',
                'type'     => 'event',
                // Ostern = erster Sonntag nach dem ersten Vollmond nach dem 21. März
                // Gaußsche Osterformel wird im Scheduler berechnet
                'criteria' => json_encode([
                    'rule'        => 'easter',
                    'days_before' => 14,
                    'days_after'  => 2,
                ]),
            ],
            [
                'slug'     => 'event-muttertag',
                'name'     => 'Muttertag',
                'subtitle' => 'Danke sagen auf die schönste Art',
                'icon'     => '💐',
                'type'     => 'event',
                // 2. Sonntag im Mai
                'criteria' => json_encode([
                    'rule'        => 'nth_weekday',
                    'month'       => 5,
                    'weekday'     => 0, // 0 = Sonntag (Carbon)
                    'nth'         => 2,
                    'days_before' => 14,
                    'days_after'  => 1,
                ]),
            ],
            [
                'slug'     => 'event-vatertag',
                'name'     => 'Vatertag',
                'subtitle' => 'Für den Mann, der alles gibt',
                'icon'     => '🍺',
                'type'     => 'event',
                // In DE = Christi Himmelfahrt = 39 Tage nach Ostersonntag
                'criteria' => json_encode([
                    'rule'        => 'easter_offset',
                    'offset_days' => 39,
                    'days_before' => 14,
                    'days_after'  => 1,
                ]),
            ],
            [
                'slug'     => 'event-halloween',
                'name'     => 'Halloween',
                'subtitle' => 'Gruselig gute Deals',
                'icon'     => '🎃',
                'type'     => 'event',
                'criteria' => json_encode([
                    'rule'        => 'fixed',
                    'month'       => 10,
                    'day'         => 31,
                    'days_before' => 14,
                    'days_after'  => 1,
                ]),
            ],
            [
                'slug'     => 'event-weihnachten',
                'name'     => 'Weihnachten',
                'subtitle' => 'Geschenke, Stimmung & Winterzauber',
                'icon'     => '🎄',
                'type'     => 'event',
                // Längeres Fenster — Weihnachtsgeschäft beginnt früh
                'criteria' => json_encode([
                    'rule'        => 'fixed',
                    'month'       => 12,
                    'day'         => 25,
                    'days_before' => 30,
                    'days_after'  => 3,
                ]),
            ],
        ];

        // Alle zusammenbauen und einfügen
        $rows = [];
        foreach (array_merge($seasons, $daytimes, $events) as $data) {
            $rows[] = array_merge([
                'category_id' => null,
                'hero_image'  => null,
                'description' => null,
                'opens_at'    => null,
                'closes_at'   => null,
                'is_active'   => false,
                'sort_order'  => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ], $data);
        }

        DB::table('hotspots')->insert($rows);
    }
}
