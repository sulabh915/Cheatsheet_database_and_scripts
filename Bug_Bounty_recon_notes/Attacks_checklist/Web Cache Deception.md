When you visit a website (like a news site or an online game), your browser sends a request to the website’s server. Some pages — like the homepage or logo image — **don’t change often**, so instead of creating them every time from scratch, the server stores a **cached copy**.
So next time, it just sends that copy. It’s **much faster** and saves time & energy!

Caches use a **cache key** to determine whether a request matches something already stored.

🧩 A cache key is usually made from:

- **URL path**: `/assets/logo.png`
- **Query parameters**: `?v=3`
- Possibly:
    - **Headers** (like `Accept-Language`)
    - **Cookies**
    - **Content type**

If two requests generate the **same cache key**, the cache assumes the response can be reused.

Caches don't store everything. They follow rules to decide what’s okay to save:
- **file extensions**: `.css`, `.js`, `.jpg`, `.png` — these are assumed to be safe and unchanging.
- **Static directories**: URLs starting with `/static/`, `/assets/`, `/images/`
- **Specific files**: like `robots.txt` or `favicon.ico`

❌ Dynamic Content Should NOT Be Cached:

Things like:

- `/account`
- `/checkout`
- `/profile`

These return **user-specific or sensitive** data and should be served fresh every time.

for example:
```bash

https://example.com/account #this can't saved cache because it is private and dynamic content

https://example.com/account/sneaky.jpg #this is saved cache because is static content
```


Steps to identify web cache deception:
- Find a Target Endpoint that returns dynamic data
```bash
/account
/dashboard
/profile
/settings
```
The GET, HEAD, or OPTIONS methods are interesting here because They're safe to cache (unlike POST or PUT).


- Identify a discrepancy in url handling
```bash
/profile → dynamic
/profile/fake.png → still returns profile data!


https://target.com/account
https://target.com/account/fake.css

Then, **get a logged-in user (the victim)** to click on it — or you simulate it using **Burp** while authenticated.
If the server **returns your real data**, and the cache **stores it**, that’s the jackpot.
Later, **you visit the same URL**, and the cached version is served — even though **you're not logged in**.

```


###### Using cache buster to properly test every request
1. Go to: `Extensions > BApp Store > Install "Param Miner"`
2. Then: `Top Menu > Param Miner > Settings > Add Dynamic Cachebuster`

##### Detecting if response are cached

X-Cache: hit	✅ Served from cache
X-Cache: miss	❌ Not cached before, now maybe is
X-Cache: dynamic	🚫 Not cacheable, came fresh
X-Cache: refresh	♻️ Cache was updated with new data
Cache-Control:	public, max-age=3600 → can be cached

1. Send a crafted URL.
2. It responds with `X-Cache: miss`.
3. Send it **again** — now you see `X-Cache: hit`.

###### Use response time as a clue
1. You find `/profile` shows a logged-in user's email and ID.
2. You try `/profile/fake.css` → it still shows that data.
3. You send this crafted request while logged in:
```bash
GET /profile/fake.css
```
4. Returns your sensitive info, and header says `X-Cache: miss`.
5. You send it again:
    
    → `X-Cache: hit`, and now the info is available without login.
    

Congratulations, that’s a successful **Web Cache Deception** exploit.




Take a private, dynamic URL, like:
/user/123/profile
​
Add a fake static-looking file extension:
/user/123/profile/wcd.css
​
Make a victim visit this.
If the origin server still returns the user’s profile info, but the cache treats this like a .css file and stores it…
The attacker can later request the same URL and get the cached private info.

###### Origin Servers Often Use REST-Style URLs
Modern apps often don’t care about the file name or extension.
```bash
/user/123/profile/wcd.css
```

> Oh, this is just another way to access /user/123/profile. Ignore the wcd.css at the end.”
> 

If the origin server is RESTful (like many APIs and web apps today), it **does not treat the path like a file system**.

```bash
/api/orders/123 → returns order info ✅

/api/orders/123/abc → still returns order info? ✅
```

###### How to know it was cached
- `X-Cache: hit` → 🟢 came from cache
- `Cache-Control: public, max-age=600` → 🟢 cacheable
- Or observe a **faster response time** the second time you send the request

… and the server returns **private profile data** but the cache stores it like it’s a public file…
…then the attacker can just visit the same URL and get **cached private data** — without authentication.