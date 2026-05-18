# CLAUDE.md — AdsApp.store

## Projekt
Pull-basierte Werbeplattform. Ads steigen/sinken organisch durch User-Signale (Views, Dwell-Time, Bookmarks, Sales). Kein Push-Advertising, kein Algorithmus-Manipulation.

**Live:** adsapp.store | **Local:** http://adsapp-store.local  
**Stack:** Laravel 13 / PHP 8.3 / MySQL 8 / Tailwind / Meilisearch / Hetzner CAX21 / Forge / Cloudflare

## Rollen
`buyer` `merchant` `creator` `agency` `admin` — alle in `users.role`, rollenspezifische Daten in eigenen Tabellen (1:1)

## Kern-Tabellen (MVP)
`users` · `merchants` · `ads` · `ad_images` · `categories` · `tags` · `ad_tag` · `bookmarks` · `ad_events` · `orders` · `commissions` · `premium_slots` · `hotspots` · `hotspot_ads`

## Konventionen
- Conventional Commits: `feat:` `fix:` `refactor:` `chore:`
- Soft Deletes überall wo Daten für Provisions-Nachverfolgung relevant
- `ad_events` ist append-only — niemals updaten, nur insertn
- Bilder werden als URLs gespeichert, nicht hochgeladen (`ad_images.remote_url`)
- Score wird periodisch aggregiert in `ads.current_score`, nicht live berechnet

## Do NOT
- Nicht auto-committen
- Nichts an `ad_events` updaten oder löschen
- Keine direkten DB-Queries außerhalb von Eloquent/QueryBuilder
- `.env` niemals anfassen