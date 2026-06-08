<?php

namespace App\Console\Commands;

use App\Models\Ad;
use App\Models\Hotspot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncHotspots extends Command
{
    protected $signature   = 'hotspots:sync';
    protected $description = 'Aktiviert/deaktiviert Kalender-Hotspots und managed dynamische Kategorie-Hotspots';

    public function handle(): void
    {
        $this->syncSaisonal();
        $this->syncTageszeit();
        $this->syncEvents();
        $this->syncDynamic();

        $this->info('hotspots:sync abgeschlossen — ' . now()->toDateTimeString());
    }

    // ══════════════════════════════════════════════
    // JAHRESZEITEN — aktiv wenn heutiges Datum im MM-DD-Fenster
    // ══════════════════════════════════════════════
    private function syncSaisonal(): void
    {
        $hotspots = Hotspot::where('type', 'saisonal')->get();
        $today    = now();

        foreach ($hotspots as $hs) {
            $criteria       = $hs->criteria;
            $shouldBeActive = $this->dateInRange(
                $today,
                $criteria['start'],
                $criteria['end'],
                $criteria['wraps_year'] ?? false
            );

            $currentlyActive = $hs->is_active; // Accessor nutzen

            if ($currentlyActive === $shouldBeActive) {
                // Bereits im richtigen Zustand — closes_at nachpflegen falls null
                if ($shouldBeActive && is_null($hs->closes_at)) {
                    $year = (int) $today->format('Y');
                    [$endMonth, $endDay] = explode('-', $criteria['end']);
                    $hs->update([
                        'closes_at' => Carbon::createFromDate($year, (int)$endMonth, (int)$endDay)->endOfDay(),
                    ]);
                    $this->line("Saisonal [{$hs->slug}] → closes_at nachgepflegt");
                }
                continue;
            }

            if ($shouldBeActive) {
                // Aktivieren: opens_at = heute Mitternacht, closes_at = Saisonende dieses Jahres
                $year = (int) $today->format('Y');
                [$endMonth, $endDay] = explode('-', $criteria['end']);
                $hs->update([
                    'opens_at'  => $today->copy()->startOfDay(),
                    'closes_at' => Carbon::createFromDate($year, (int)$endMonth, (int)$endDay)->endOfDay(),
                ]);
            } else {
                // Deaktivieren: closes_at auf gestern setzen
                $hs->update(['closes_at' => $today->copy()->subDay()->endOfDay()]);
            }

            $this->line("Saisonal [{$hs->slug}] → " . ($shouldBeActive ? 'ON' : 'OFF'));
        }
    }

    // ══════════════════════════════════════════════
    // TAGESZEITEN — aktiv wenn aktuelle Stunde im Fenster
    // ══════════════════════════════════════════════
    private function syncTageszeit(): void
    {
        $hotspots    = Hotspot::where('type', 'tageszeit')->get();
        $now         = now();
        $currentHour = (int) $now->format('H');

        foreach ($hotspots as $hs) {
            $criteria      = $hs->criteria;
            $start         = (int) $criteria['start_hour'];
            $end           = (int) $criteria['end_hour'];
            $wrapsMidnight = $criteria['wraps_midnight'] ?? false;

            if ($wrapsMidnight) {
                $shouldBeActive = $currentHour >= $start || $currentHour <= $end;
            } else {
                $shouldBeActive = $currentHour >= $start && $currentHour < $end;
            }

            $currentlyActive = $hs->is_active;

            if ($currentlyActive === $shouldBeActive) {
                if ($shouldBeActive && is_null($hs->closes_at)) {
                    $closesAt = $wrapsMidnight
                        ? $now->copy()->addDay()->setTime($end, 0)
                        : $now->copy()->setTime($end, 0);
                    $hs->update(['opens_at' => $now, 'closes_at' => $closesAt]);
                    $this->line("Tageszeit [{$hs->slug}] → closes_at nachgepflegt");
                }
                continue;
            }

            if ($shouldBeActive) {
                // Fenster öffnen: opens_at = jetzt, closes_at = heute um end_hour Uhr
                $closesAt = $wrapsMidnight
                    ? $now->copy()->addDay()->setTime($end, 0)
                    : $now->copy()->setTime($end, 0);

                $hs->update(['opens_at' => $now, 'closes_at' => $closesAt]);
            } else {
                $hs->update(['closes_at' => $now->copy()->subMinute()]);
            }

            $this->line("Tageszeit [{$hs->slug}] → " . ($shouldBeActive ? 'ON' : 'OFF'));
        }
    }

    // ══════════════════════════════════════════════
    // FESTLICHKEITEN — Datum per Regel berechnen, Fenster aktivieren
    // ══════════════════════════════════════════════
    private function syncEvents(): void
    {
        $hotspots = Hotspot::where('type', 'event')->get();
        $today    = now()->startOfDay();

        foreach ($hotspots as $hs) {
            $criteria     = $hs->criteria;
            $eventDate    = $this->resolveEventDate($criteria, (int) $today->year);
            $daysBefore   = (int) ($criteria['days_before'] ?? 14);
            $daysAfter    = (int) ($criteria['days_after']  ?? 1);

            $windowStart  = $eventDate->copy()->subDays($daysBefore);
            $windowEnd    = $eventDate->copy()->addDays($daysAfter)->endOfDay();

            $shouldBeActive = $today->between($windowStart, $windowEnd);

            if ($hs->is_active !== $shouldBeActive) {
                $hs->update([
                    'opens_at'  => $shouldBeActive ? $windowStart : now()->subDay(),
                    'closes_at' => $windowEnd,
                ]);
                $this->line("Event [{$hs->slug}] → " . ($shouldBeActive ? 'ON' : 'OFF') . " (Event: {$eventDate->toDateString()})");
            }
        }
    }

    // ══════════════════════════════════════════════
    // DYNAMISCHE KATEGORIE-HOTSPOTS
    // Trigger: ≥4 Ads einer Kategorie mit score > 1.5× Kategorie-Median (24h Events)
    // Schließen: qualifizierte Ads fallen unter 4
    // Kühlperiode: 48h nach Schließen kein Re-Trigger
    // ══════════════════════════════════════════════
    private function syncDynamic(): void
    {
        $since = now()->subHours(24);

        // 24h-Activity-Score pro Ad berechnen (nur ad_events der letzten 24h)
        // Gewichtung: view=1, dwell=3, sale=10 — identisch zu RecalculateAdScores
        $activityScores = DB::table('ad_events')
            ->select('ad_id', DB::raw("
                SUM(CASE
                    WHEN event_type = 'view'  THEN 1
                    WHEN event_type = 'dwell' THEN 3
                    WHEN event_type = 'sale'  THEN 10
                    ELSE 0
                END) as activity_score
            "))
            ->where('created_at', '>=', $since)
            ->groupBy('ad_id')
            ->pluck('activity_score', 'ad_id'); // [ad_id => score]

        // Alle aktiven Ads mit ihren Kategorie-Infos laden
        $ads = Ad::where('status', 'active')
            ->whereNotNull('category_id')
            ->get(['id', 'category_id']);

        // Ads nach Kategorie gruppieren + Activity-Score zuordnen
        $byCategory = $ads->groupBy('category_id')->map(function ($catAds) use ($activityScores) {
            return $catAds->map(fn($ad) => [
                'id'    => $ad->id,
                'score' => $activityScores[$ad->id] ?? 0,
            ]);
        });

        foreach ($byCategory as $categoryId => $catAds) {
            $scores = $catAds->pluck('score');

            // Median berechnen
            $sorted = $scores->sort()->values();
            $count  = $sorted->count();
            $median = $count % 2 === 0
                ? ($sorted[$count / 2 - 1] + $sorted[$count / 2]) / 2
                : $sorted[(int) ($count / 2)];

            // Threshold: 1.5× Median, mindestens 1 (sonst triggert jede Kategorie mit Null-Aktivität)
            $threshold = max(1, $median * 1.5);

            // Qualifizierte Ads: score > threshold
            $qualifiedAdIds = $catAds->filter(fn($a) => $a['score'] > $threshold)
                ->pluck('id')
                ->values();

            // Existierenden dynamischen Hotspot für diese Kategorie suchen
            $existing = Hotspot::where('type', 'dynamisch')
                ->where('category_id', $categoryId)
                ->first();

            if ($qualifiedAdIds->count() >= 4) {
                // Trigger-Bedingung erfüllt
                if (!$existing) {
                    // Kategorie-Namen holen für Hotspot-Name
                    $categoryName = DB::table('categories')->where('id', $categoryId)->value('name') ?? "Kategorie {$categoryId}";

                    $existing = Hotspot::create([
                        'type'        => 'dynamisch',
                        'category_id' => $categoryId,
                        'slug'        => 'dynamic-cat-' . $categoryId . '-' . now()->timestamp,
                        'name'        => "🔥 {$categoryName}",
                        'subtitle'    => 'Gerade besonders gefragt',
                        'icon'        => '🔥',
                        'opens_at'  => now(),
                        'closes_at' => null,
                        'criteria'    => ['threshold_multiplier' => 1.5, 'min_ads' => 4],
                    ]);

                    $this->line("Dynamisch [cat:{$categoryId}] → ERSTELLT & ON");
                } elseif (!$existing->is_active) {
                    // Kühlperiode prüfen: closes_at muss > 48h her sein
                    $cooledDown = !$existing->closes_at
                        || $existing->closes_at->diffInHours(now()) >= 48;

                    if ($cooledDown) {
                        $existing->update(['opens_at' => now(), 'closes_at' => null]);
                        $this->line("Dynamisch [cat:{$categoryId}] → RE-AKTIVIERT");
                    }
                }

                // Pivot aktualisieren: alte Einträge raus, neue rein
                if ($existing) {
                    DB::table('hotspot_ads')->where('hotspot_id', $existing->id)->delete();
                    $pivotRows = $qualifiedAdIds->map(fn($adId) => [
                        'hotspot_id' => $existing->id,
                        'ad_id'      => $adId,
                        'added_at'   => now(),
                    ])->toArray();
                    DB::table('hotspot_ads')->insert($pivotRows);
                }
            } elseif ($existing && $existing->is_active) {
                // Unter Schwelle — Hotspot schließen
                $existing->update(['closes_at' => now()]);
                DB::table('hotspot_ads')->where('hotspot_id', $existing->id)->delete();
                $this->line("Dynamisch [cat:{$categoryId}] → GESCHLOSSEN (unter Schwelle)");
            }
        }
    }

    // ══════════════════════════════════════════════
    // HELPERS
    // ══════════════════════════════════════════════

    // Prüft ob $date im MM-DD-Fenster liegt (jahresneutral)
    private function dateInRange(Carbon $date, string $start, string $end, bool $wrapsYear = false): bool
    {
        $year     = (int) $date->format('Y');
        $mmdd     = $date->format('m-d');

        if ($wrapsYear) {
            // Winter: aktiv wenn >= 12-01 ODER <= 02-28
            return $mmdd >= $start || $mmdd <= $end;
        }

        return $mmdd >= $start && $mmdd <= $end;
    }

    // Berechnet das konkrete Datum einer Festlichkeit für ein gegebenes Jahr
    private function resolveEventDate(array $criteria, int $year): Carbon
    {
        return match ($criteria['rule']) {
            // Festes Datum: z.B. Valentinstag 14.02
            'fixed' => Carbon::createFromDate($year, $criteria['month'], $criteria['day']),

            // Ostern per Gaußscher Formel
            'easter' => $this->calculateEaster($year),

            // Versatz von Ostersonntag: z.B. Vatertag = Ostersonntag + 39 Tage
            'easter_offset' => $this->calculateEaster($year)->addDays($criteria['offset_days']),

            // N-ter Wochentag im Monat: z.B. 2. Sonntag im Mai (Muttertag)
            'nth_weekday' => $this->nthWeekdayOfMonth($year, $criteria['month'], $criteria['weekday'], $criteria['nth']),

            default => throw new \InvalidArgumentException("Unbekannte Datums-Regel: {$criteria['rule']}"),
        };
    }

    // Gaußsche Osterformel — gibt Ostersonntag als Carbon zurück
    private function calculateEaster(int $year): Carbon
    {
        $a = $year % 19;
        $b = (int) ($year / 100);
        $c = $year % 100;
        $d = (int) ($b / 4);
        $e = $b % 4;
        $f = (int) (($b + 8) / 25);
        $g = (int) (($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = (int) ($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = (int) (($a + 11 * $h + 22 * $l) / 451);
        $month = (int) (($h + $l - 7 * $m + 114) / 31);
        $day   = (($h + $l - 7 * $m + 114) % 31) + 1;

        return Carbon::createFromDate($year, $month, $day);
    }

    // N-ter Wochentag eines Monats: nth=2, weekday=0 (So) → 2. Sonntag
    private function nthWeekdayOfMonth(int $year, int $month, int $weekday, int $nth): Carbon
    {
        $date  = Carbon::createFromDate($year, $month, 1);
        $count = 0;

        while ($count < $nth) {
            if ($date->dayOfWeek === $weekday) {
                $count++;
                if ($count === $nth) break;
            }
            $date->addDay();
        }

        return $date;
    }
}
