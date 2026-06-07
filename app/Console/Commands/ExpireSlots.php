<?php

namespace App\Console\Commands;

use App\Models\SlotBooking;
use Illuminate\Console\Command;

class ExpireSlots extends Command
{
    protected $signature   = 'slots:expire';
    protected $description = 'Setzt abgelaufene live-Buchungen auf expired';

    public function handle(): void
    {
        $count = SlotBooking::where('status', 'live')
            ->where('ends_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Erledigt: {$count} Buchung(en) auf expired gesetzt.");
    }
}
