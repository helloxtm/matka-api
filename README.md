# Matka API — Client Integration Kit (MAPI)

Upload this folder to your server (e.g. `public_html/matka-api`). It fetches live results from **https://www.matkaapi.com/mapi/** using your **domain_key** — same as the [Postman collection](postman/mapi.json).

No database is required to run cron URLs. JSON is returned so you can save data yourself, or use optional `db_save.php` examples.

## Requirements

- PHP 7.4+ with **curl** extension
- Active plan on [matkaapi.com](https://www.matkaapi.com)
- **domain_key** from Dashboard → Domains (`user_domain.unique_key`)
- Server IP whitelisted in dashboard (first cron call may auto-save IP)

## Quick setup

1. Upload entire `matka-api` folder to your hosting.
2. Edit `config.php`:
   ```php
   'domain_key' => 'your_unique_key_from_dashboard',
   'domain' => 'yourdomain.com',  // optional; auto-detected from URL
   ```
3. Open in browser: `https://yourdomain.com/matka-api/index.php` — should return JSON with `mapi_test.status: true`.
4. Set cron jobs (examples below).

## Folder structure

```
matka-api/
  config.php              ← your domain_key
  lib/                    ← shared MAPI client (do not edit)
  market/                 ← Matka / market results
  starline/               ← Starline / fatafat
  satta/                  ← Satta / disawar
  database/               ← optional DB credentials
  postman/mapi.json       ← API reference
  docs/DATABASE.md        ← how to save to MySQL
```

## Cron URLs (hit from browser or server cron)

| Module | URL | MAPI call |
|--------|-----|-----------|
| Market — today all | `/matka-api/market/update_today.php` | `market=all` |
| Market — list | `/matka-api/market/update_list.php` | `market_list=1` |
| Market — single | `/matka-api/market/update_single.php?market=mainbazar` | one market |
| Starline — today all | `/matka-api/starline/update_today.php` | `game=all` |
| Starline — list | `/matka-api/starline/update_list.php` | `game_list=1` |
| Starline — single | `/matka-api/starline/update_single.php?game=kalyanstarline` | one game |
| Satta — today all | `/matka-api/satta/update_today.php` | `game=all` |
| Satta — list | `/matka-api/satta/update_list.php` | `satta_list=1` |
| Satta — single | `/matka-api/satta/update_single.php?game=GALI` | one game |

### Example crontab (every 5 minutes)

```cron
*/5 * * * * curl -s "https://yourdomain.com/matka-api/market/update_today.php" >> /tmp/matka-market.log
*/5 * * * * curl -s "https://yourdomain.com/matka-api/starline/update_today.php" >> /tmp/matka-starline.log
*/5 * * * * curl -s "https://yourdomain.com/matka-api/satta/update_today.php" >> /tmp/matka-satta.log
```

## Response format

Success:

```json
{
  "status": true,
  "message": "Data fetched from MAPI",
  "db_saved": false,
  "mapi": { "status": true, "data": [ ... ] }
}
```

Use `mapi.data` to insert into your database (see `docs/DATABASE.md`).

## Optional: auto-save to MySQL

1. Copy `database/config.example.php` → `database/config.php` (credentials).
2. In each module copy `db_save.example.php` → `db_save.php`.
3. Run `schema_example.sql` in that module (or map to your tables).
4. In `config.php` set `'save_to_database' => true`.

Cron scripts will call your `db_save.php` after each successful MAPI fetch.

## MAPI reference

Full params and yearly-plan rules (`list=1`, `date=`) match **postman/mapi.json**:

- `market_api.php`
- `starline_api.php`
- `satta_api.php`

Auth: **domain_key** only (per domain).

## Support

WhatsApp: [8100304443](https://wa.gowebs.in)
