<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ad;
use Illuminate\Support\Facades\DB;

class RecalculateAdScores extends Command
{
    protected $signature   = 'ads:recalculate-scores';
    protected $description = 'Berechnet current_score für alle aktiven Ads aus AdEvents + Bookmarks';

    // Gewichtung pro Event-Typ
    private array $weights = [
        'view'    => 1,
        'dwell'   => 3,
        'bounce'  => -1,  // kurze Absprünge leicht negativ
        'sale'    => 10,
        'refund'  => -5,
    ];

    private int $bookmarkWeight = 5;
    private int $windowDays    = 30; // nur Events der letzten 30 Tage

    public function handle(): void
    {
        $this->info('Score-Berechnung gestartet...');

        $ads = Ad::withTrashed(false) // keine soft-deleted Ads
        ->whereIn('status', ['active', 'paused'])
            ->get();

        $cutoff = now()->subDays($this->windowDays);
        $bar    = $this->output->createProgressBar($ads->count());
        $bar->start();

        foreach ($ads as $ad) {
            // Event-Score: gewichtete Summe aus ad_events
            $eventScore = DB::table('ad_events')
                ->where('ad_id', $ad->id)
                ->where('created_at', '>=', $cutoff)
                ->select('event_type', DB::raw('count(*) as cnt'))
                ->groupBy('event_type')
                ->get()
                ->reduce(function (float $carry, object $row): float {
                    $w = $this->weights[$row->event_type] ?? 0;
                    return $carry + ($w * $row->cnt);
                }, 0.0);

            // Bookmark-Score: aktuelle Bookmark-Anzahl × Gewicht
            $bookmarkCount = DB::table('bookmarks')
                ->where('ad_id', $ad->id)
                ->count();

            $raw = $eventScore + ($bookmarkCount * $this->bookmarkWeight);

            // Score auf 0–99.99 normalisieren (sigmoid-ähnlich)
            // Formel: score = 99.99 * (raw / (raw + 100))
            // Bei raw=0 → 0, bei raw=100 → ~50, bei raw=∞ → 99.99
            $score = $raw > 0
                ? round(99.99 * ($raw / ($raw + 100)), 2)
                : 0.00;

            $ad->update([
                'current_score'    => $score,
                'last_activity_at' => now(),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ {$ads->count()} Ads aktualisiert.");
    }
}
