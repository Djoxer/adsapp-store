# AdsApp.store

Pull-based ad catalog platform. Ads rise or sink organically through real user signals — no push, no interruption, no ad fatigue. Buyers browse voluntarily, merchants list for free, revenue only on sales.

## Concept

Built on the idea that advertising works better when users opt in. Instead of interrupting content, ads live in their own voluntary space with an organic ranking algorithm driven by views, dwell time, bookmarks and sales. The platform connects three groups: buyers who browse and purchase, merchants who list their products, and agencies who manage multiple merchant accounts.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13 / PHP 8.3 |
| Database | MySQL 8 |
| Search | Meilisearch (self-hosted) |
| Frontend | Blade / Tailwind CSS |
| Server | Hetzner CAX21 ARM |
| Deploy | Laravel Forge |
| CDN / DNS | Cloudflare |

## Architecture

4-zone catalog model:
- **Open Pond** — organic main catalog, score-ranked
- **Hotspots** — curated thematic zones (seasonal, local, event-based)
- **Ufersteine** — limited premium slots, fixed price, score minimum required
- **Ticker** — trending discovery strip

## Status

🚧 Active development — Capstone project FA Anwendungsentwicklung 2026–2028

## Local Setup

```bash
git clone git@github.com:Djoxer/adsapp-store.git
cd adsapp-store
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Live

[adsapp.store](https://adsapp.store) — coming soon
