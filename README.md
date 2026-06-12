# Matka API — Integration Kit (User Guide)

Yeh PHP kit aapke **apne server** par upload karke Matka API se live results auto-fetch karta hai.

- **API source:** `https://www.matkaapi.com/mapi/`
- **Auth:** `domain_key` (har domain ka alag key)
- **Reference:** `postman/mapi.json` (Postman collection — same URLs & params)

Database **zaroori nahi** — pehle cron chalao, JSON dekho, phir apne DB mein save karo (guide: `docs/DATABASE.md`).

---

## Table of contents

1. [Plan purchase / recharge](#1-plan-purchase--recharge)
2. [Price Details](#2-price-details)
3. [Dashboard setup (domain + domain_key)](#3-dashboard-setup-domain--domain_key)
4. [Server requirements](#4-server-requirements)
5. [Upload & configure this kit](#5-upload--configure-this-kit)
6. [Test connection](#6-test-connection)
7. [Cron URLs — Market / Starline / Satta](#7-cron-urls--market--starline--satta)
8. [MAPI parameters (API detail)](#8-mapi-parameters-api-detail)
9. [Plan limits (today vs old data)](#9-plan-limits-today-vs-old-data)
10. [Rate limit, cache & auto block](#10-rate-limit-cache--auto-block)
11. [Save data to your database](#11-save-data-to-your-database)
12. [Common errors](#12-common-errors)
13. [Support](#13-support)

---

## 1. Plan purchase / recharge

1. Open [https://www.matkaapi.com](https://www.matkaapi.com)
2. **Login / Register** → Dashboard
3. **Domains** — apna website domain add karo (e.g. `yourdomain.com`)
4. **Price / Plans** se plan choose karo — full pricing [Price Details](#2-price-details) mein
5. Payment complete hone ke baad plan **active** ho jata hai (`f_from` – `t_to` dates dashboard par dikhengi)
6. **Recharge / renew:** same dashboard se plan extend karo jab `Plan expired` error aaye

> Har domain ka **alag plan** aur **alag request count** hota hai. Request limit dashboard par track hoti hai.

---

## 2. Price Details

Plans [matkaapi.com](https://www.matkaapi.com) par — **har domain** ke liye alag plan activate hota hai.

### Plan & pricing

| Plan | Duration | Price | Integration fees |
|------|----------|-------|------------------|
| **Monthly** | 30 days | **₹499** / month | **₹1,500** |
| **Half-Yearly** | 184 days | **₹1,999** / 6 months *(Save 50%)* | **₹1,000** |
| **Yearly** | 365 days | **₹4,999** / 1 year | **₹799** |

**Integration fees** = one-time setup help (yeh PHP kit upload, `config.php`, cron, optional database). Longer plan par fees kam.

### Features comparison

| Feature | Monthly | Half-Yearly | Yearly |
|---------|:-------:|:-----------:|:------:|
| All API (Market, Starline, Satta, Teer) | ✅ | ✅ | ✅ |
| PHP KIT (yeh folder) | ✅ | ✅ | ✅ |
| 2 Way API | ✅ | ✅ | ✅ |
| Old Chart (`date=` + `list=1` history) | ❌ | ❌ | ✅ |
| 24/7 Support | ❌ | ❌ | ✅ |

### API access by plan

| Plan | Today live result | Previous date (`date=`) | Full history (`list=1`) |
|------|-------------------|-------------------------|-------------------------|
| Monthly | ✅ | ❌ | ❌ |
| Half-Yearly | ✅ | ❌ | ❌ |
| Yearly | ✅ | ✅ | ✅ (max 500 rows) |

Monthly / Half-Yearly par sirf **aaj ka result** API se milega. Purana chart / old date ke liye **Yearly plan** lo.

Plan buy ya integration help: WhatsApp [7205225513](https://wa.me/917205225513)

---

## 3. Dashboard setup (domain + domain_key)

### Domain add

1. Dashboard → **Domains**
2. Add domain: `yourdomain.com` (without `http://` or `www`)
3. Copy **Domain Key** (`unique_key`) — yahi `domain_key` hai

### Auth rules (Postman vs server)

MAPI request **device** ya **server** detect karta hai:

| Call from | Security | `domain` param |
|-----------|----------|----------------|
| **Postman / browser / app** | IP whitelist + **domain must match key** | Required (ya browser Referer same domain) |
| **Server** (cron, PHP curl, yeh kit) | IP whitelist + **domain match required** | **Required** — dashboard domain jaisa |

> `abc.com` se call + `hello.com` ka `domain_key` = **block** (`Domain not matched`).

Server-side example:

```
?market=all&domain_key=YOUR_KEY&domain=yourdomain.com
```

Postman example (`domain` = key owner domain):

```
?market=all&domain_key=YOUR_KEY&domain=yourdomain.com
```

### IP whitelist (important)

API sirf **whitelist IP** se data deti hai:

| Case | Kya hota hai |
|------|----------------|
| IP empty (first time) | Server IP auto-save ho sakti hai |
| IP match | Result aata hai |
| IP mismatch | Error: *Please update your IP from dashboard* |

**Cron server ka public IP** dashboard → Domains → IP field mein set karo.

> Postman = aapka PC/mobile IP whitelist karo. Cron = hosting server IP whitelist karo.

Rate limit / auto block detail: [Section 10](#10-rate-limit-cache--auto-block).

### config.php mein yeh values

```php
'domain_key' => 'paste_domain_key_from_dashboard',
'domain'     => 'yourdomain.com',  // optional — URL se auto-detect bhi hota hai
```

---

## 4. Server requirements

- PHP **7.4+**
- PHP extension: **curl**
- Folder writable (optional, sirf logs ke liye)
- HTTPS recommended

---

## 5. Upload & configure this kit

### Step 1 — Upload

Poora `matka-api` folder upload karo, e.g.:

```
public_html/matka-api/
```

Folder structure **change mat karo** — `lib/`, `market/`, `starline/`, `satta/` same rehna chahiye.

### Step 2 — Config

```bash
# Agar config.php nahi hai:
cp config.example.php config.php
```

Edit `config.php`:

| Key | Description |
|-----|-------------|
| `mapi_base_url` | `https://www.matkaapi.com` (default) |
| `domain_key` | Dashboard → Domains → Domain Key |
| `domain` | Apna domain (khali chhod sakte ho) |
| `save_to_database` | `false` = sirf JSON; `true` = `db_save.php` use karo |

### Step 3 — File permissions

PHP files executable via web server (normal hosting default OK).

---

## 6. Test connection

Browser mein open karo:

```
https://yourdomain.com/matka-api/index.php
```

**Success example:**

```json
{
  "status": true,
  "domain_key_set": true,
  "mapi_test": {
    "status": true,
    "type": "market_list",
    "data": [ ... ]
  }
}
```

Agar `mapi_test.status: false` — [Common errors](#12-common-errors) dekho.

Setup ke baad security ke liye `index.php` hata sakte ho ya password protect karo.

---

## 7. Cron URLs — Market / Starline / Satta

Base path: `https://yourdomain.com/matka-api/`

Har URL MAPI ko call karta hai aur JSON return karta hai. `mapi.data` mein results hain.

### Market (`market_api.php`)

| Cron file | Browser URL | API |
|-----------|-------------|-----|
| Today — all markets | `market/update_today.php` | `market=all` |
| Market list | `market/update_list.php` | `market_list=1` |
| Single market | `market/update_single.php?market=mainbazar` | `market=mainbazar` |

**Direct MAPI (reference):**

```
GET https://www.matkaapi.com/mapi/market_api.php?market=all&domain_key=YOUR_KEY
GET https://www.matkaapi.com/mapi/market_api.php?market_list=1&domain_key=YOUR_KEY
GET https://www.matkaapi.com/mapi/market_api.php?market=mainbazar&domain_key=YOUR_KEY
```

### Starline (`starline_api.php`)

| Cron file | Browser URL | API |
|-----------|-------------|-----|
| Today — all games | `starline/update_today.php` | `game=all` |
| Game list | `starline/update_list.php` | `game_list=1` |
| Single game (all slots) | `starline/update_single.php?game=kalyanstarline` | `game=...` |

**Direct MAPI:**

```
GET https://www.matkaapi.com/mapi/starline_api.php?game=all&domain_key=YOUR_KEY
GET https://www.matkaapi.com/mapi/starline_api.php?game_list=1&domain_key=YOUR_KEY
GET https://www.matkaapi.com/mapi/starline_api.php?game=kalyanstarline&baji=1&domain_key=YOUR_KEY
```

### Satta (`satta_api.php`)

| Cron file | Browser URL | API |
|-----------|-------------|-----|
| Today — all games | `satta/update_today.php` | `game=all` |
| Satta list | `satta/update_list.php` | `satta_list=1` |
| Single game | `satta/update_single.php?game=GALI` | `game=GALI` |

**Direct MAPI:**

```
GET https://www.matkaapi.com/mapi/satta_api.php?game=all&domain_key=YOUR_KEY
GET https://www.matkaapi.com/mapi/satta_api.php?satta_list=1&domain_key=YOUR_KEY
GET https://www.matkaapi.com/mapi/satta_api.php?game=GALI&domain_key=YOUR_KEY
```

### Server cron (har 5 minute example)

```cron
*/5 * * * * curl -s "https://yourdomain.com/matka-api/market/update_today.php" >> /tmp/matka-market.log 2>&1
*/5 * * * * curl -s "https://yourdomain.com/matka-api/starline/update_today.php" >> /tmp/matka-starline.log 2>&1
*/5 * * * * curl -s "https://yourdomain.com/matka-api/satta/update_today.php" >> /tmp/matka-satta.log 2>&1
```

cPanel → Cron Jobs mein bhi same URLs add kar sakte ho.

> **Rate limit:** MAPI par 30 sec min gap + flood block hai — cron **5 min** safe hai; 1–2 sec mat chalao. Detail: [Section 10](#10-rate-limit-cache--auto-block).

### Kit response format

```json
{
  "status": true,
  "message": "Data fetched from MAPI",
  "db_saved": false,
  "db_message": "",
  "mapi": {
    "status": true,
    "message": "success",
    "date": "2026-06-12",
    "count": 10,
    "data": [ ... ]
  }
}
```

Apne website par dikhane ke liye `mapi.data` use karo ya database save enable karo.

---

## 8. MAPI parameters (API detail)

Auth (har request):

| Param | Required | Description |
|-------|----------|-------------|
| `domain_key` | Yes | Dashboard → Domains → unique_key |
| `domain` | Optional | Live site par Referer se verify; cron par optional |

### Market API — `mapi/market_api.php`

| Param | Example | Use |
|-------|---------|-----|
| `market_list` | `1` | Sab markets ki list |
| `market` | `mainbazar` / `all` | Result |
| `date` | `2026-06-10` | Old date (yearly plan) |
| `list` | `1` | Full history max 500 (yearly) |

**Today response fields:** `name`, `result`, `date`, `open_time`, `close_time`

### Starline API — `mapi/starline_api.php`

| Param | Example | Use |
|-------|---------|-----|
| `game_list` | `1` | Active games |
| `baji_list` / `slots` | `1` | Slots + time for one game |
| `game` | `kalyanstarline` / `all` | Results |
| `baji` / `slot` | `1` | Single slot |
| `date` | `2026-06-10` | Old date (yearly) |
| `list` | `1` | History (yearly) |

**Result fields:** `game`, `slot`, `time`, `patti`, `sd`, `result`

### Satta API — `mapi/satta_api.php`

| Param | Example | Use |
|-------|---------|-----|
| `satta_list` | `1` | Game list |
| `game` / `satta` | `GALI` / `all` | Results |
| `date` | `2026-06-10` | Old date (yearly) |
| `list` | `1` | History — row mein `date` DD-MM-YYYY |

**Today fields:** `name`, `result`, `time`

POST bhi supported — same params form body mein.

Full Postman tests: import `postman/mapi.json` in Postman, set variable `domain_key`.

---

## 9. Plan limits (today vs old data)

| Plan (`package.month`) | Today result | Previous `date` | `list=1` history |
|------------------------|----------------|-----------------|------------------|
| Trial `0` / Monthly `1` / `6` | Yes | No | No |
| Yearly `12` | Yes | Yes | Yes (max 500) |

Monthly plan par old data error:

```
OLD Data not available for Monthly Plan
```

Request limit full hone par:

```
Your Request Limit Reached. Contact admin.
```

→ Dashboard se plan recharge / upgrade karo.

---

## 10. Rate limit, cache & auto block

`matkaapi.com` MAPI par **public protection** active hai — zyada fast calls se server slow / down hone se bachne ke liye.

### Rate limit

| Rule | Detail |
|------|--------|
| Min gap | **30 seconds** — same API + same params dubara call mat karo |
| Error | HTTP **429**, `error_code: rate_limited` |
| Wait | Response mein `retry_after` (seconds) — uske baad dubara try karo |
| Repeat abuse | 5 bar 30 sec rule break → **15 min auto block** |

**Example error:**

```json
{
  "status": false,
  "message": "Too many requests. Minimum interval is 30 seconds.",
  "error_code": "rate_limited",
  "retry_after": 22,
  "min_interval_sec": 30
}
```

**Safe intervals (recommended):**

| Use | Interval |
|-----|----------|
| Server cron (yeh kit) | **5–15 minutes** |
| Website live refresh | **30–60 seconds** |
| Postman test | On demand (30 sec gap same request par) |

> 1–2 second polling mat karo — result har second change nahi hota, API block ho sakti hai.

### Response cache (45 sec)

- Same request ka answer **45 sec** cache hota hai
- Response mein `cached: true` → normal hai, fresh DB/scrape skip
- Cache hit par plan **request count (`total_req`) nahi badhta**

### Auto block + alert

Bahut tez / flood calls par **temporary block** (15 minutes):

| Trigger | Limit |
|---------|-------|
| Per domain | **40+ calls / minute** |
| Per IP | **80+ calls / minute** |
| Rate limit repeat | 5 violations (30 sec rule) |

**Block error:**

```json
{
  "status": false,
  "message": "Too many requests. Temporarily blocked.",
  "error_code": "temp_blocked",
  "retry_after": 900,
  "reason": "flood_per_minute"
}
```

**Admin alert log** (matkaapi.com server par, kit par nahi):

```
mapi/cache/alerts.log
```

Is file mein `RATE_LIMIT` aur `AUTO_BLOCK` entries admin dekh sakta hai — suspicious domain/IP track karne ke liye.

### Kit / cron ke liye tip

- Yeh kit `domain` + `X-Matka-Source: server` bhejta hai — server rules apply
- Cron **har 5 min** enough hai (`market/update_today.php` etc.)
- Agar `rate_limited` aaye → cron interval badhao, `retry_after` respect karo
- Apne server par fast loop / 1 sec poll mat chalao jo MAPI ko bar-bar hit kare

---

## 11. Save data to your database

Kit by default **database use nahi karta**.

### Quick path (recommended for beginners)

1. Cron URL browser mein kholo → JSON copy karo
2. `mapi.data` ko apne PHP/MySQL code se insert karo
3. Details + field mapping: **`docs/DATABASE.md`**

### Auto-save path (advanced)

1. `database/config.example.php` → `database/config.php` (MySQL user/pass)
2. Har module: `db_save.example.php` → `db_save.php` (edit table names)
3. `schema_example.sql` run karo (market / starline / satta folder)
4. `config.php`: `'save_to_database' => true`
5. Cron chalao — response mein `db_saved: true` aana chahiye

Aap apne existing tables use kar sakte ho — sirf `db_save.php` mein INSERT/UPDATE edit karo.

---

## 12. Common errors

| Message | Solution |
|---------|----------|
| `domain_key is required` / empty | `config.php` mein domain_key set karo |
| `Invalid domain key` | Dashboard se sahi key copy karo |
| `Please update your IP from dashboard` | Server public IP dashboard mein add karo |
| `Domain not matched` | Server call par `domain=` dashboard domain se match hona chahiye |
| `domain is required` | `domain=yourdomain.com` add karo (key owner domain) |
| `domain is required for server-side requests` | Cron/curl/kit se call — `domain=` key owner domain |
| `Plan expired or not active` | Plan recharge karo |
| `Your Request Limit Reached` | Plan upgrade / limit badhao |
| `OLD Data not available for Monthly Plan` | Yearly plan lo ya sirf today cron chalao |
| `rate_limited` / HTTP 429 | Same call 30 sec ke andar dubara mat karo; `retry_after` wait karo |
| `temp_blocked` | Bahut fast calls — 15 min wait; check `mapi/cache/alerts.log` (server) |
| `cached: true` in response | Normal — cached data (45 sec), plan count nahi badhta |
| `PHP curl extension is required` | Hosting par curl enable karo |
| `save_to_database` but no save | `db_save.php` copy karo + `database/config.php` |

---

## 13. Support

- Website: [https://www.matkaapi.com](https://www.matkaapi.com)
- WhatsApp: [7205225513](https://wa.me/917205225513)
- API docs on site: Integration Guide section
- Postman: `postman/mapi.json` in this folder

---

## Folder map

```
matka-api/
├── config.php                 ← domain_key (required)
├── index.php                  ← connection test
├── lib/                       ← MAPI client (do not edit)
├── market/
│   ├── update_today.php       ← cron: all markets today
│   ├── update_list.php
│   ├── update_single.php
│   ├── db_save.example.php    ← copy to db_save.php
│   └── schema_example.sql
├── starline/
│   ├── update_today.php
│   ├── update_list.php
│   ├── update_single.php
│   ├── db_save.example.php
│   └── schema_example.sql
├── satta/
│   ├── update_today.php
│   ├── update_list.php
│   ├── update_single.php
│   ├── db_save.example.php
│   └── schema_example.sql
├── database/
│   └── config.example.php     ← MySQL (optional)
├── postman/mapi.json          ← full API collection
└── docs/DATABASE.md           ← DB insert guide
```

**Summary:** Plan lo → domain_key + IP set karo → kit upload → `config.php` → cron URLs chalao → `mapi.data` apni site / database mein use karo.
