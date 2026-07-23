# Matka API — Direct URL Guide

Direct API call se live results — **Market**, **Starline**, **Satta**, **Teer**.

- **Website:** [https://www.matkaapi.com](https://www.matkaapi.com)
- **API base:** `https://www.matkaapi.com/mapi/`
- **Postman:** `Matka_API.json` · [Online collection](https://www.postman.com/gowebs/matka-api-satta-matka-auto-result-api)
- **WhatsApp:** [7205225513](https://wa.me/917205225513)

---

## Quick start

| Step | Kya karna hai |
|------|----------------|
| 1 | **Testing API** — Free Trial se key lo |
| 2 | Browser / Postman se **direct URL** call karo |
| 3 | Live ke liye **Production** plan + Domain Key |

Har request mein yeh params:

```
domain_key=YOUR_KEY
domain=yourdomain.com
```

---

## 1. Testing API (Free Trial)

### Page

[https://www.matkaapi.com/trail-up.html](https://www.matkaapi.com/trail-up.html)

### Video — poora Trial process

**[Watch on YouTube](https://youtu.be/6hKgjkBy_VQ)**

### Steps

1. Trial page open karo
2. **Name** · **Mobile** (10 digit) · **Domain** (`yourdomain.com`)
3. Terms accept → **Submit** → OTP verify
4. Milega: **Domain** + **Trial Key** (`domain_key`)

OTP: **3 / day** · resend wait **30s → 1 min**

> Trial: `domain` required · **IP check nahi**

**Trial example:**

```
https://www.matkaapi.com/mapi/market_api.php?market_list=1&domain_key=YOUR_TRIAL_KEY&domain=yourdomain.com
```

Already account? [Sign In](https://www.matkaapi.com/sign-in.html)

---

## 2. Production API

1. [matkaapi.com](https://www.matkaapi.com) → Login
2. Dashboard → **Domains** → domain add
3. Plan buy → **Domain Key** copy
4. Dashboard mein **IP whitelist** set karo (server / PC public IP)

| Call from | `domain_key` | `domain` | IP |
|-----------|:------------:|:--------:|----|
| Browser / Postman | Required | Optional | Whitelist |
| Server / cron / PHP | Required | **Required** | Whitelist |

**Production example:**

```
https://www.matkaapi.com/mapi/market_api.php?market=all&domain_key=YOUR_KEY&domain=yourdomain.com
```

### Pricing

| Plan | Duration | Price | Integration fees |
|------|----------|-------|------------------|
| Monthly | 30 days | ₹499 | ₹1,500 |
| Half-Yearly | 184 days | ₹1,999 | ₹1,000 |
| Yearly | 365 days | ₹4,999 | ₹799 |

| Feature | Monthly | Half-Yearly | Yearly |
|---------|:-------:|:-----------:|:------:|
| All API | ✅ | ✅ | ✅ |
| Today live result | ✅ | ✅ | ✅ |
| Old date / history | ❌ | ❌ | ✅ |
| 24/7 Support | ❌ | ❌ | ✅ |

---

## 3. Direct API URLs

`KEY` = Trial Key ya Production Key  
`DOMAIN` = aapka domain (e.g. `yourdomain.com`)

Same URL Testing aur Production dono ke liye — sirf key badlo.

### Market

```
https://www.matkaapi.com/mapi/market_api.php?market_list=1&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/market_api.php?market=mainbazar&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/market_api.php?market=all&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/market_api.php?market=mainbazar&date=2026-06-10&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/market_api.php?market=mainbazar&list=1&domain_key=KEY&domain=DOMAIN
```

| Param | Use |
|-------|-----|
| `market_list=1` | Sab markets list |
| `market=mainbazar` | Single market today |
| `market=all` | Sab markets today |
| `date=YYYY-MM-DD` | Purani date *(Yearly only)* |
| `list=1` | Full history *(Yearly only)* |

### Starline

```
https://www.matkaapi.com/mapi/starline_api.php?game_list=1&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/starline_api.php?game=kalyanstarline&baji_list=1&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/starline_api.php?game=kalyanstarline&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/starline_api.php?game=kalyanstarline&baji=1&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/starline_api.php?game=all&domain_key=KEY&domain=DOMAIN
```

### Satta

```
https://www.matkaapi.com/mapi/satta_api.php?satta_list=1&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/satta_api.php?game=GALI&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/satta_api.php?game=all&domain_key=KEY&domain=DOMAIN
```

### Teer

```
https://www.matkaapi.com/mapi/teer_api.php?game_list=1&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/teer_api.php?game=Shillong%20Teer&domain_key=KEY&domain=DOMAIN
https://www.matkaapi.com/mapi/teer_api.php?game=all&domain_key=KEY&domain=DOMAIN
```

GET aur POST dono OK (POST = same params form body).

Full request list: import **`Matka_API.json`** in Postman.

> Same URL **30 sec** ke andar dubara mat hit karo (rate limit).

---

## 4. Common errors

| Message | Fix |
|---------|-----|
| `domain_key is required` | URL mein key add karo |
| `Invalid domain key` | Trial / Dashboard se sahi key copy karo |
| `domain is required for server-side requests` | `&domain=yourdomain.com` add karo |
| `Please update your IP from dashboard` | Production IP whitelist update |
| `Plan expired` | Plan recharge |
| `OLD Data not available for Monthly Plan` | History ke liye Yearly plan |
| `rate_limited` (429) | `retry_after` wait karo |

---

## Summary

1. Trial: [trail-up.html](https://www.matkaapi.com/trail-up.html) → OTP → Trial Key · Video: [youtu.be/6hKgjkBy_VQ](https://youtu.be/6hKgjkBy_VQ)
2. Call: direct `https://www.matkaapi.com/mapi/...` URL
3. Production: Dashboard Domain Key + IP
4. Postman: `Matka_API.json`

Support: [matkaapi.com](https://www.matkaapi.com) · WhatsApp [7205225513](https://wa.me/917205225513)
