<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\Merchant;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    protected $model = Ad::class;

    private array $titles = [
        'NEXUS CTRL V3','VOID HEADSET PRO','SHADOW-FLYER Mk.III','CORE_SWITCH ELITE',
        'TERRA-FORM ULTRA','SYNTH PROTEIN X','NIGHT-PROWL Z','ATMOS HUB MINI',
        'VISION-CORE 27','HYPER-LINK BOARD','BIO-GEL PACK PRO','GHOST PARKA v2',
        'PLASMA DESK LAMP','QUANTUM MOUSE PAD','SIGNAL BOOST AMP','DRIFT CHAIR MK2',
        'ECHO SPEAKER CUBE','NANO LENS KIT','PULSE MONITOR V4','SOLAR CHARGE PAD',
        'TITAN BACKPACK','CRYO COOLING RIG','VORTEX FAN TOWER','APEX WEBCAM 4K',
        'NOVA KEYBOARD 75%','SENTRY CAM MINI','FLUX POWER BANK','IRIS SMART LIGHT',
        'ZENITH HEADBAND','ORBIT DESK MAT',
    ];

    public function definition(): array
    {
        return [
            'merchant_id'     => Merchant::inRandomOrder()->first()?->id ?? 1,
            'category_id'     => Category::inRandomOrder()->first()?->id ?? 1,
            'title'           => $this->faker->unique()->randomElement($this->titles)
                . ' ' . strtoupper($this->faker->lexify('??')),
            'description'     => $this->faker->sentences(2, true),
            'price_cents'     => $this->faker->randomElement([
                2990, 4990, 8990, 12900, 24900, 49900,
                89900, 124900, 24900000, 189900, 39900
            ]),
            'deeplink_url'    => $this->faker->url(),
            'status'          => 'active',
            'current_score'   => $this->faker->randomFloat(2, 1.0, 99.99),
            'last_activity_at'=> $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'paused']);
    }
}
