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


> [!NOTE] Title
> - Always test for WCD in **RESTful APIs** and modern SPAs (Single Page Apps).
> -  For traditional apps, test **only if** there's evidence of dynamic path resolution.

#### Delimiter
- `?` → separates **URL path** from **query string**
- `;` → used in Java Spring for **matrix parameters**
- `.` → used in Ruby on Rails to specify **response format**
- `%00` → null byte, used by some servers to **truncate** URLs

- The **Java Spring** server sees:
    
    > /profile + matrix param foo.css → returns profile info
    > 
- But the **cache** (like Akamai or Cloudflare) sees:
    
    > a static .css file → “Cool! Let’s cache this.” ✅


```bash
/profile%00foo.js
```

- **OpenLiteSpeed** sees `%00` (null byte) as: “stop reading here” → `/profile`
- **Cache** (Akamai or Fastly) sees the whole thing: `/profile%00foo.js`
    - Ends in `.js` → ✅ might cache it

```bash
/settings/users/list;aaa
```

- If the server still gives the same response → `;` is a **delimiter** (like in Java Spring)
- If it gives an error → `;` is **not** a delimiter

	Origin Server	/settings/users/list → returns dynamic profile HTML
	Cache	/settings/users/list;aaa.js → looks like a .js file → stored ✅



> [!NOTE] Using tools

> - **Burp Suite Intruder**: Automate testing different delimiters and extensions.
    - Use payloads like: `;`, `.`, `%00`, `/`, `=`, , `_`
- **Disable automatic encoding** in Burp when testing characters like `%00` — or it may change your input.
- **Monitor headers**:
    - `X-Cache: HIT` → confirms response is cached.
    - `Cache-Control: public` + `max-age` → confirms cacheability.

| Character        | Encoded form |
| ---------------- | ------------ |
| `?`              | `%3F`        |
| `#`              | `%23`        |
| `;`              | `%3B`        |
| `\0` (null byte) | `%00`        |
| Line feed        | `%0A`        |
| Tab              | `%09`        |
- The **cache** looks at the **raw, encoded URL**.
- The **origin server** decodes the URL **before** routing it.
- **Cache sees**: `profile%23wcd.css` → ends in `.css` → ✅ cache
- **Server sees**: `profile#wcd.css` → URL **truncates at `#`** → serves `/profile` (dynamic)

Origin Server:

- Decodes `%23` into `#`
- Treats `#` as a **fragment delimiter**
- Truncates to `/profile`
- Returns **sensitive profile info**

🔹 Cache Server:

- Doesn’t decode `%23`
- Sees `.css` at the end
- Thinks it’s a **static file**
- Stores the response for everyone

➡️ Now anyone accessing `/profile%23wcd.css` gets the **cached private info**.


List of payload to test for web cache deception :

```bash
!
"
#
$
%
&
'
(
)
*
+
,
-
.
/
:
;
<
=
>
?
@
[
\
]
^
_
`
{
|
}
~
%21
%22
%23
%24
%25
%26
%27
%28
%29
%2A
%2B
%2C
%2D
%2E
%2F
%3A
%3B
%3C
%3D
%3E
%3F
%40
%5B
%5C
%5D
%5E
%5F
%60
%7B
%7C
%7D
%7E
```


#### Exploiting static directory cache rules :

```bash
/assets/..%2fprofile
```

Origin Server:

- Decodes `%2f` into `/`
- Resolves `..` to go **up one directory**
- Final path = `/profile`
- Returns **sensitive profile data**

❌ Cache:

- Doesn’t decode `%2f`
- Doesn’t resolve `..`
- Sees this as just another **file under `/assets`**
- Sees `.html` or `.css` in the response (maybe)
- **Caches the dynamic response** at:

/assets/..%2fprofile

- Is used by the **origin server** to **truncate the path** (e.g., `;`)
- ❌ Is ignored by the **cache** (so the cache processes the full normalized path)
```bash
/profile;%2f%2e%2e%2fstatic
```

| Component | Behavior |
| --- | --- |
| **Origin server** | Sees `;` → **truncates path at `/profile`** → returns **dynamic profile info** |
| **Cache** | Sees full path → decodes `%2f%2e%2e%2f` into `/../` → resolves to `/static` → caches it |

- The **origin server doesn’t decode** the traversal — it doesn’t see `/../`
- But it **does** recognize the **delimiter** `;`, and cuts the URL short
- The **cache** decodes `%2f%2e%2e%2f` into `/../`, resolves it, and thinks the request is for **`/static`**

#### Exploiting file name cache rules
| File name | Why it’s cached |
| --- | --- |
| `robots.txt` | Rarely changes, crawled by bots |
| `favicon.ico` | Used by browsers, expected to be static |
| `index.html` | Default landing page |
Trick the cache into thinking you're accessing a safe, public file like /index.html, when you're actually getting private, dynamic content (like /profile) — and have it cached.\

| System | What it does |
| --- | --- |
| **Cache** | Decodes and normalizes: `/index.html`  → ✅ **Cache rule matches**, response cached |
| **Origin server** | Doesn’t decode `%2f`, keeps path literal → `/profile%2f%2e%2e%2findex.html` → interpreted as a request to `/profile%2f%2e%2e%2findex.html` → may return **profile page** or even 404 |

| Step | What Happens |
| --- | --- |
| Cache | Decodes and normalizes `/profile%2f%2e%2e%2findex.html` to `/index.html` and applies cache rules ✅ |
| Origin server | Doesn’t decode traversal — may treat as `/profile`, returns dynamic info ❌ |
| Result | Dynamic content (e.g., profile) is cached as `/index.html`, and can be leaked to others |