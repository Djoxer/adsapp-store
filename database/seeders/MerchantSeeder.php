<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::where('role', 'merchant')
            ->orWhere('role', 'agency')
            ->each(function ($u) {
                if (!$u->merchant) {
                    \App\Models\Merchant::create([
                        'user_id'         => $u->id,
                        'company_name'    => $u->name,
                        'shop_url'        => '',
                        'approval_status' => 'pending',
                    ]);
                }
            });
    }
}
