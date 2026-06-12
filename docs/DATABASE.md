# Database integration guide

This kit **does not connect to MySQL by default**. Cron scripts only call Matka API and return JSON.

You choose how to store `mapi.data` in your own database.

---

## Option A — Manual / your own code

After calling a cron URL, parse JSON:

```php
$json = file_get_contents('https://yourdomain.com/matka-api/market/update_today.php');
$res = json_decode($json, true);
if (!($res['status'] ?? false)) {
    die($res['message'] ?? 'error');
}
$rows = $res['mapi']['data'] ?? [];
foreach ($rows as $row) {
    // INSERT/UPDATE your table using $row['name'], $row['result'], etc.
}
```

### Market row fields (today / single date)

| Field | Example |
|-------|---------|
| name | MAIN BAZAR |
| result | 558-84-789 |
| date | 2026-06-12 |
| open_time | 03:00 PM |
| close_time | 05:00 PM |

### Starline row fields

| Field | Example |
|-------|---------|
| game | KOLKATA FF |
| slot | 1 |
| time | 10:30 AM |
| patti | 233 |
| sd | 8 |
| result | 233-8 |

### Satta row fields (today)

| Field | Example |
|-------|---------|
| name | GALI |
| result | 66 |
| time | 11:25 PM |

History (`list=1`): `date` as `11-06-2026` per row instead of `time`.

---

## Option B — Use provided examples (PDO)

### Step 1 — Database config

```bash
cp database/config.example.php database/config.php
```

Edit host, name, user, pass.

### Step 2 — Create tables

Run SQL from each module (adjust names to match your site):

- `market/schema_example.sql`
- `starline/schema_example.sql`
- `satta/schema_example.sql`

### Step 3 — Enable save handlers

Per module:

```bash
cp market/db_save.example.php market/db_save.php
cp starline/db_save.example.php starline/db_save.php
cp satta/db_save.example.php satta/db_save.php
```

Edit `db_save.php` if your column names differ.

### Step 4 — Enable in config.php

```php
'save_to_database' => true,
```

### Step 5 — Test

Open `market/update_today.php` — response should include:

```json
"db_saved": true,
"db_message": "Database save completed"
```

---

## Mapping to your existing tables

If you already have tables (e.g. from old matka-api), edit only `db_save.php`:

1. Change table/column names in the `INSERT` statements.
2. Keep the same MAPI field mapping from `$mapiResponse['data']`.
3. Use `ON DUPLICATE KEY UPDATE` or your own upsert logic.

### Example: single market result upsert (mysqli)

```php
$name = $row['name'];
$result = $row['result'];
$date = $row['date'];
mysqli_query($link, "
  INSERT INTO my_market (market_name, result, date)
  VALUES ('$name', '$result', '$date')
  ON DUPLICATE KEY UPDATE result = VALUES(result)
");
```

**Use prepared statements in production** (see `db_save.example.php`).

---

## Yearly plan — history

MAPI params (12 month plan only):

- `list=1` — full history (max 500)
- `date=YYYY-MM-DD` — specific past date

You can add cron scripts that call `MapiClient` with extra params, e.g.:

```php
$client->get('market_api.php', ['market' => 'mainbazar', 'list' => '1']);
```

Or extend `update_single.php` with `date` / `list` GET params in your copy.

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| Invalid domain key | Check `config.php` domain_key |
| Please update your IP | Set server IP in matkaapi dashboard |
| Plan expired | Renew plan for that domain |
| db_saved false | Check `database/config.php` and `db_save.php` |
| OLD Data not available | Monthly plan — only today; use yearly for history |
