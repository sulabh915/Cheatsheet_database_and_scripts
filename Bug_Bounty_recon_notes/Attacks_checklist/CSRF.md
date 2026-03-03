
Cross-Site Request Forgery (CSRF) is a web security vulnerability where an attacker tricks a logged-in user into making an unintended request to a web application — with the user’s credentials.
If an attacker gets you to unknowingly send that exact request — say, by clicking a link or visiting a malicious page — your browser will attach your session cookie, and your bank will think it’s you making the transfer.

1. **CSRF Tokens**: Include a random, secret token in each form. Attackers can’t guess it.
2. **SameSite Cookies**: Set cookies to `SameSite=Strict` or `Lax`, which blocks them from being sent in cross-site requests.
3. **Double Submit Cookies**: Send the CSRF token in both a cookie and a form value and verify both.
4. **User Interaction Requirements**: Asking for password re-entry for sensitive actions
5. no CSRF token, no confirmation, no password re-entry. leads to csrf




Step by step 
1. Intercept the real request to `/email/change`.
2. Right-click it → **Engagement tools → Generate CSRF PoC**.
3. Burp generates the malicious HTML for you.
4. Copy it into a `.html` file and open it in a browser where you're logged into the vulnerable site.
5. If the CSRF is successful, your email is changed.


| Attack Type | Goal | How It Works | Can Read Response? | Requires User Login? |
| --- | --- | --- | --- | --- |
| **XSS** (Cross-Site Scripting) | Run code (JavaScript) in the victim’s browser | Attacker injects JS into a page (via URL, input, etc.) | ✅ Yes | ❌ Not required |
| **CSRF** (Cross-Site Request Forgery) | Trick user into submitting a request | Victim’s browser sends a request using **their cookies** | ❌ No | ✅ Yes |



###### Why GET Requests Make CSRF Easier
When a sensitive action (like changing an email address or transferring money) can be triggered via a GET request, the attacker doesn’t need to use a form or JavaScript. They can simply create a link or embed an image that causes the victim’s browser to issue the request automatically.


https://vulnerable-website.com/email/change?email=pwned@evil-user.net


CSRF using img :
```bash
<html>
  <body>
    <!-- This image tag triggers the CSRF automatically -->
    <img src="https://vulnerable-website.com/email/change?email=pwned@evil-user.net" alt="Loading image...">
  </body>
</html>

```


1. The victim is **already logged in** to `vulnerable-website.com`.
2. Their browser loads the page and tries to fetch the image.
3. The `img` tag triggers a **GET request** to `https://vulnerable-website.com/email/change?...`.
4. The browser **sends the victim’s cookies** with the request.
5. The server sees a valid session and processes the email change.

##### CSRF Token mistake #1
CSRF token only validated for POST, but not for GET requests

1. The browser loads the image (or iframe).
2. Sends the **GET request** to the vulnerable site **with the user's session cookie**.
3. No CSRF token is checked.
4. The server processes the request: ✅ email changed.

##### CSRF Token mistake #2
Problem: CSRF Token Validation Only Happens If the Token Exists
If the attacker removes the csrf parameter completely, the server skips CSRF validation entirely!


If the server only checks the token when it is present, and doesn’t enforce its presence, the attacker just omits the csrf parameter and bypasses protection.


Fix
1. Rejects the request if the token is **missing** ❌
2. Rejects the request if the token is **invalid** ❌

Only when both are correct ✅ does the server allow the action.



##### CSRF Token mistake #3
Problem: CSRF Token Is Valid Globally, Not Per User Session

**Unique per session**

✅ **Mapped to the logged-in user**

✅ **Valid only when sent by the same user who received it**

But in some badly designed systems:

❌ The token is **not tied to the user's session**

❌ The server just keeps a list of “issued tokens” and accepts any of them from anyone


1. Victim is logged into `vulnerable.com`
2. They visit the attacker's page
3. Their browser sends:
    - **Valid session cookie** (for victim)
    - **Attacker’s CSRF token**
4. The server sees:
    - "Hey, that CSRF token is on our global list!"
    - And allows the request — **even though it’s not the victim’s token**

If the attacker can plant their own CSRF cookie into the victim’s browser, and then trigger a request using their CSRF token — the server accepts it, even though it doesn't match the victim’s session.


##### CSRF Token mistake #5
 What Is the Double Submit Cookie CSRF Defense?

In this method, when a user logs in:

1. The server sends:
    - A **session cookie**
    - A **CSRF token in another cookie** (e.g., `csrf=XYZ`)
2. When submitting a form, the browser sends:
    - The CSRF token **in a form field**
    - The **same token** in a cookie
3. The server just checks:
    
    ```python
    python
    CopyEdit
    if request.form['csrf'] == request.cookies['csrf']:
        # allow the request
    
    ```
    

✅ This avoids server-side state — **but...**

---

 🔓 What’s the Flaw?

If an attacker can **set the `csrf` cookie** in the victim’s browser (e.g., via a vulnerable subdomain or header injection), they can:

- Pick **any value they want** for the CSRF token
- Inject that same value into:
    - A cookie (`csrf=attacker_token`)
    - The request (form field `csrf=attacker_token`)

So... the server sees:

```

csrf cookie: attacker_token
csrf form param: attacker_token
✅ They match → request allowed

```

💥 **No protection at all**


#### Understanding SameSite :

A site is defined as the top-level domain (TLD), usually something like .com or .net, plus one additional level of the domain name. This is often referred to as the TLD+1.  When determining whether a request is same-site or not, the URL scheme is also taken into consideration. This means that a link from [http://app.example.com](http://app.example.com/) to [https://app.example.com](https://app.example.com/) is treated as cross-site by most browsers. 

same tld + same scheme = same site

###### Step by Step process

Set-Cookie: session=abc123; SameSite=Strict
💥 With SameSite=Strict, the browser refuses to send the session cookie.


SameSite=Lax — Balanced Approach (Chrome default)


> [!NOTE] Title
> When there is no samesite parameter cookie is set,  by default chrome set samesite=lax to the cookie but this rule only works after 120 seconds , so within in 2 minute windows cross site POST request is valid. 

`Secure` ensures that:

> The cookie is only ever sent over HTTPS, and never in plain HTTP.


Without SameSite:

- Browser sends the session cookie
- `bank.com` processes it as if it came from the victim
- ✅ Email is changed

With `SameSite=Lax` or `Strict`:

- Browser blocks the session cookie
- ❌ `bank.com` treats it as unauthenticated
- ✅ CSRF attack fails


| Cookie Header | HTTPS Cross-Site Request | HTTP Request | Sent in iframe/fetch? |
| --- | --- | --- | --- |
| `SameSite=Strict` | ❌ No | ❌ No | ❌ No |
| `SameSite=Lax` | ✅ Only GET clicks | ❌ No | ❌ No |
| `SameSite=None; Secure` | ✅ Yes | ❌ Rejected | ✅ Yes |
| `SameSite=None` (missing Secure) | ❌ Rejected by browser | ❌ Rejected | ❌ Rejected |


| Cookie Setting | Sent with JS/POST/iframe? | Sent with GET link? | Secure? | CSRF Resistant? |
| --- | --- | --- | --- | --- |
| `SameSite=Strict` | ❌ No | ❌ No | No | ✅ Very Strong |
| `SameSite=Lax` (Chrome default) | ❌ No (unless top-level GET) | ✅ Yes | No | ✅ Good |
| `SameSite=None; Secure` | ✅ Yes | ✅ Yes | ✅ Yes | ❌ None |
| No attribute (Chrome default = Lax) | ❌ No (POST/JS) | ✅ Yes | No | ✅ Good-ish |



##### Best for authentication
Set-Cookie: session=abc123; SameSite=Strict; HttpOnly; Secure

###### Acceptable balance
Set-Cookie: session=abc123; SameSite=Lax; HttpOnly; Secure

##### For 3rd-party embedded usage (analytics, chat)
Set-Cookie: tracking=xyz; SameSite=None; Secure


> [!NOTE] Remember
> ALWAYS use CSRF tokens in your forms for real security.
> SameSite just reduces risk — but if a vulnerability (like XSS) leaks cookies or tokens, CSRF is still possible.




#### Bypassing SameSite Lax restrictions using GET requests
- Session cookie uses: `SameSite=Lax` (explicit or default in Chrome)
- Server processes sensitive actions via GET (bad practice)
- Request is a top-level navigation (like a link or JS redirect)

```bash

<script>
  document.location = 'https://vulnerable-website.com/account/transfer-payment?recipient=hacker&amount=1000000';
</script>
```


###### 🎯 Scenario 2: Overriding the Method in a POST Form

But the endpoint is only exposed in a form designed to submit as POST, like:
```bash
<form action="/account/transfer-payment" method="POST">
  <input type="hidden" name="recipient" value="bob">
  <input type="hidden" name="amount" value="100">
</form>

```


```bash
<form action="https://vulnerable-website.com/account/transfer-payment" method="POST">
  <input type="hidden" name="_method" value="GET">
  <input type="hidden" name="recipient" value="hacker">
  <input type="hidden" name="amount" value="1000000">
</form>
<script>document.forms[0].submit();</script>

```


1. The attacker submits a POST request
2. Symfony sees `_method=GET`
3. Symfony **rewrites this as a GET internally**
4. Browser includes session cookie (because it’s same-site)
5. ✅ Server treats it as a legitimate request from the victim


###### Bypassing SameSite restrictions via vulnerable sibling domains
Key Concept: "Same-Site" ≠ "Same-Origin"

Browsers consider two different subdomains to be same-site if they share the same eTLD+1 (like example.com).

- `https://shop.example.com`
- `https://mail.example.com`

These are **same-site**, even though they’re **different origins** (because hostnames differ).


1. Attacker tricks the victim into clicking that link.
2. XSS runs in the context of `blog.example.com`
3. It sends a **GET request** to `bank.example.com`
4. Since `bank.example.com` and `blog.example.com` are **same-site**, the browser **includes the session cookie**, even if it’s `SameSite=Strict`
5. 💥 Account is drained.


```bash
fetch("https://bank.example.com/transfer?to=hacker&amount=10000", {
  method: "GET",
  credentials: "include" // include session cookies
});

```

- Because **blog.example.com and bank.example.com are same-site**
- And the browser doesn’t care if **origins are different**
- So `SameSite=Strict` still allows the cookie to be sent!


- **Audit all sibling subdomains** — they share the SameSite risk
- **Patch XSS** vulnerabilities across all subdomains
- **Use CSRF tokens**, not just SameSite cookies
- For WebSockets:
    - Use a **custom Origin header check** on the server
    - Do **token-based authentication** inside the WebSocket, not just cookies