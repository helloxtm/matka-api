# Matka API — Simple Guide

Live results API for **Market**, **Starline**, **Satta**, and **Teer**.

- **Website:** [https://www.matkaapi.com](https://www.matkaapi.com)
- **API base:** `https://www.matkaapi.com/mapi/`
- **Postman:** `postman/Matka_API.json` · [Online collection](https://www.postman.com/gowebs/matka-api-satta-matka-auto-result-api)
- **WhatsApp support:** [7205225513](https://wa.me/917205225513)

---

## Quick start

| Step | What to do |
|------|------------|
| 1 | **Testing API** lo — Free Trial (neeche process) |
| 2 | Postman mein `domain_key` set karke API test karo |
| 3 | Ready ho to **Production** plan buy karo + domain key use karo |

---

## 1. Testing API (Free Trial)

Trial se aap **testing / demo** ke liye API key milta hai — bina paid plan ke.

### Trial page

- Live: [https://www.matkaapi.com/trail-up.html](https://www.matkaapi.com/trail-up.html)
- Local copy (api-solutions): `trail-up.html`

### Video — poora Trial process

Poora Trial registration (details → OTP → key) is video mein dikhaya gaya hai:

**[Watch: Trial process (YouTube)](https://youtu.be/6hKgjkBy_VQ)**

### Simple Trial steps

1. Open [trail-up.html](https://www.matkaapi.com/trail-up.html)
2. Fill form:
   - **Name**
   - **Mobile** (10 digit)
   - **Domain** (e.g. `yourdomain.com` — without `http://` / `www`)
3. Accept **Terms, Privacy and Fees**
4. **Submit** → OTP WhatsApp/SMS par aayega
5. 4-digit OTP enter karo → **VERIFY OTP**
6. Success screen par milega:
   - **Domain**
   - **Trial Key** (`domain_key`)

OTP rules: **3 OTP / day** · resend wait **30s → 1 min**.

### Trial key kaise use karein

Trial activated hone ke baad:

| Param | Value |
|-------|--------|
| `domain_key` | Trial Key (success screen se) |
| `domain` | Wahi domain jo form mein diya |

> Trial: **server calls par `domain` required** · **IP check nahi** hota.

**Example (Market list):**

```
https://www.matkaapi.com/mapi/market_api.php?market_list=1&domain_key=YOUR_TRIAL_KEY&domain=yourdomain.com
```

Postman mein:

1. Import `postman/Matka_API.json`
2. Variables set karo:
   - `baseUrl` = `https://www.matkaapi.com`
   - `domain_key` = Trial Key
   - `domain` = aapka domain (server test ke liye)
3. Market / Starline / Satta / Teer requests chalao

Already account hai? [Sign In](https://www.matkaapi.com/sign-in.html)

---

## 2. Production API

Paid plan ke baad **production** `domain_key` milta hai — dashboard se.

### Plan lo

1. [matkaapi.com](https://www.matkaapi.com) → Login / Register
2. Dashboard → **Domains** → apna domain add karo
3. Plan choose / payment → plan active
4. **Domain Key** copy karo → yahi Production `domain_key`

### Pricing (short)

| Plan | Duration | Price | Integration fees |
|------|----------|-------|------------------|
| Monthly | 30 days | ₹499 | ₹1,500 |
| Half-Yearly | 184 days | ₹1,999 | ₹1,000 |
| Yearly | 365 days | ₹4,999 | ₹799 |

| Feature | Monthly | Half-Yearly | Yearly |
|---------|:-------:|:-----------:|:------:|
| All API (Market, Starline, Satta, Teer) | ✅ | ✅ | ✅ |
| Today live result | ✅ | ✅ | ✅ |
| Old date / history (`date=`, `list=1`) | ❌ | ❌ | ✅ |
| 24/7 Support | ❌ | ❌ | ✅ |

### Production auth

| Call from | `domain_key` | `domain` | IP |
|-----------|:------------:|:--------:|----|
| Postman / local PC | Required | Optional | Whitelist IP |
| Server / cron / PHP | Required | **Required** | Whitelist server IP |
| Live website | Required | Referer match | Whitelist |

Dashboard → Domains → **IP** field mein server / PC public IP set karo.

**Production example:**

```
https://www.matkaapi.com/mapi/market_api.php?market=all&domain_key=YOUR_KEY&domain=yourdomain.com
```

---

## 3. API endpoints (Production + Testing same)

Sab endpoints same hain — farq sirf **key** (Trial vs Production) aur plan limits ka.

Import: **`postman/Matka_API.json`**

`baseUrl` = `https://www.matkaapi.com`

### Market — `mapi/market_api.php`

| Use | URL |
|-----|-----|
| Market list | `?market_list=1&domain_key=KEY` |
| Single today | `?market=mainbazar&domain_key=KEY` |
| All today | `?market=all&domain_key=KEY` |
| Previous date* | `?market=mainbazar&date=2026-06-10&domain_key=KEY` |
| Full history* | `?market=mainbazar&list=1&domain_key=KEY` |

\* Yearly plan only

### Starline — `mapi/starline_api.php`

| Use | URL |
|-----|-----|
| Game list | `?game_list=1&domain_key=KEY` |
| Slot list | `?game=kalyanstarline&baji_list=1&domain_key=KEY` |
| Game today | `?game=kalyanstarline&domain_key=KEY` |
| One slot | `?game=kalyanstarline&baji=1&domain_key=KEY` |
| All today | `?game=all&domain_key=KEY` |

### Satta — `mapi/satta_api.php`

| Use | URL |
|-----|-----|
| Satta list | `?satta_list=1&domain_key=KEY` |
| Single today | `?game=GALI&domain_key=KEY` |
| All today | `?game=all&domain_key=KEY` |

### Teer — `mapi/teer_api.php`

| Use | URL |
|-----|-----|
| Game list | `?game_list=1&domain_key=KEY` |
| Single today | `?game=Shillong Teer&domain_key=KEY` |
| All today | `?game=all&domain_key=KEY` |

GET aur POST dono supported (POST = same params form body).

### Postman variables

| Variable | Example |
|----------|---------|
| `baseUrl` | `https://www.matkaapi.com` |
| `domain_key` | Trial Key ya Production Key |
| `domain` | `yourdomain.com` |
| `market` | `mainbazar` |
| `game` | `kalyanstarline` |
| `satta` | `GALI` |
| `teer` | `Shillong Teer` |
| `baji` | `1` |
| `date` | `2026-06-10` |

---

## 4. PHP kit (optional — apne server par)

Is folder ko upload karke cron se auto-fetch kar sakte ho.

1. Folder upload: `public_html/matka-api/`
2. `config.example.php` → `config.php`
3. Set:

```php
'domain_key' => 'YOUR_KEY',
'domain'     => 'yourdomain.com',
```

4. Test: `https://yourdomain.com/matka-api/index.php`
5. Cron (har 5 min recommended):

```
https://yourdomain.com/matka-api/market/update_today.php
https://yourdomain.com/matka-api/starline/update_today.php
https://yourdomain.com/matka-api/satta/update_today.php
```

Response JSON mein results `mapi.data` mein aate hain — apni site / DB mein jaise chaho use karo.

> Same request **30 sec** ke andar dubara mat maaro (rate limit). Cron **5–15 min** safe hai.

---

## 5. Common errors

| Message | Fix |
|---------|-----|
| `domain_key is required` | Key set karo (Trial ya Production) |
| `Invalid domain key` | Dashboard / Trial screen se sahi key copy karo |
| `domain is required for server-side requests` | URL mein `&domain=yourdomain.com` add karo |
| `Please update your IP from dashboard` | Production: IP whitelist update karo |
| `Plan expired` | Plan recharge karo |
| `OLD Data not available for Monthly Plan` | Old date / history ke liye Yearly plan |
| `rate_limited` (429) | `retry_after` seconds wait karo |

---

## Summary

1. **Testing:** [trail-up.html](https://www.matkaapi.com/trail-up.html) → OTP → Trial Key  
   Video: [https://youtu.be/6hKgjkBy_VQ](https://youtu.be/6hKgjkBy_VQ)
2. **Test API:** Postman → `postman/Matka_API.json` → `domain_key` + `domain`
3. **Production:** Dashboard plan + Domain Key + IP whitelist
4. **Live use:** Direct MAPI URLs ya yeh PHP kit cron

Support: [matkaapi.com](https://www.matkaapi.com) · WhatsApp [7205225513](https://wa.me/917205225513)
