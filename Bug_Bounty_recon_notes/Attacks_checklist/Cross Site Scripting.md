Same-Origin Policy :
The Same-Origin Policy (SOP) is a security rule enforced by browsers. It stops websites from accessing data from each other unless they share the same origin.
Origin = Protocol + Domain + Port

For example:
https://example.com:443 ≠ http://example.com:80
https://example.com ≠ https://evil.com
Only if all 3 match, they are considered the same origin.

SOP **prevents a malicious origin from _reading**_** data from another origin.**

Here’s what SOP **blocks**:

- A page from `evil.com` trying to **read** a response from `bank.com`
- A script from `attacker.com` trying to **access cookies, DOM, or response data** from `victim.com`

But SOP **does NOT block a site from sending requests** to another origin!

Requests Are Allowed — Responses Are Protected
- The browser sends the request ✅
- But the script never **needs to read** a response—it just sends data off (like a message in a bottle 📦)
- SOP **doesn’t stop sending data to other origins**, it just **blocks reading** from them


- **Stored XSS**: Malicious script is saved in the database and shown to all users (like a poisoned comment).
- **Reflected XSS**: Script is injected into a URL or form and reflected back immediately.
- **DOM-based XSS**: Vulnerability exists entirely in client-side code, often due to JavaScript mishandling.


##### How to Find and Test Reflected XSS Vulnerabilities

✅ Step 1: Test Every Input Entry Point
- URL query parameters: `?search=abc`
- POST bodies: form fields like `comment=hi`
- URL path: `/product/abc123`
- Headers: `User-Agent`, `Referer`, `X-Forwarded-For`

✅ Step 2: Inject a Unique Random Token
Use a token like:

```

xyzABC12

```

Put it into:

```

https://target.com/page?search=xyzABC12

```

Then search the response for that string.

📌 If you find it in the response, it tells you:

> “Aha! The input is reflected somewhere in the page!”

| Location | Example | Context |
| --- | --- | --- |
| In raw HTML | `<p>You searched: xyzABC12</p>` | ✅ Safe to test with HTML tag payload |
| Inside attribute | `<input value="xyzABC12">` | 🟡 Needs quote handling |
| Inside JS | `var user = "xyzABC12";` | 🔴 Dangerous! JS context needs JS escaping |
### Step 4: **Test a Basic Payload**

Try something simple first:

```html
html
CopyEdit
<script>alert(1)</script>

```

If it runs, congrats 🎉 you found reflected XSS.

You can also try payloads like:

```html
html
CopyEdit
"><script>alert(1)</script>

```

Or even inside JS:

```
js
CopyEdit
";alert(1);//

```

> 🧪 Tools: Use Burp Repeater to test requests, or manually craft URLs
> 

---

### ✅ Step 5: **Test Alternative Payloads**

Sometimes the app:

- **Filters characters** like `<` or `"`
- **Escapes some but not all input**
- **Strips known patterns** like `script`

So you test **obfuscated payloads**:

```jsx
<img src=x onerror=alert(1)>
```

```jsx
<script>eval('alert(1)')</script>
```

And encoded versions:

```jsx
<script>alert(String.fromCharCode(88,83,83))</script>
```

Burp Intruder + Burp Scanner can help fuzz many payloads automatically.

### ✅ Step 6: Confirm in a Real Browser

Even if it looks like the payload works in Burp:

Try the exact link in Chrome or Firefox

Look for alert(document.domain) (safe demo)

Make sure JavaScript actually executes, not just appears in source

| Type | What It Means | Persistence | Delivery Method |
| --- | --- | --- | --- |
| **Reflected XSS** | Input is immediately echoed back | ❌ Temporary | Link/URL-based |
| **Stored XSS** | Malicious input is stored (DB, file) | ✅ Persistent | Comments, posts, etc. |
| **Self-XSS** | Victim tricks themselves into running malicious code | ❌ Local only | Social engineering (paste in console) |
### Self-XSS = Low Impact

- The script only runs **if the user pastes it into their browser console**
- No remote execution
- Often used in **phishing tricks** like:
    
    > “Paste this to get free followers!”

# What Is Stored XSS?

> Stored XSS occurs when malicious input is:
> 
1. **Submitted by the attacker** (e.g., via form, API, or request)
2. **Saved by the server** (in a database, file, or backend storage)
3. **Later rendered** into a page viewed by **other users**, **unescaped**

Unlike **reflected XSS**, which lives only in the request, **stored XSS stays in the app** and can hit **multiple victims** — that's why it's **persisten**

| Example | Where Data May Appear |
| --- | --- |
| Blog comments | Public blog page |
| User profile name | Profile pages, post author tags, chat, admin dashboards |
| Email subject | Webmail inbox |
| Uploaded file name | Download section |
| Search history | Sidebar, autocomplete suggestions |
| Logs or audit pages | Admin-only views |

| Feature | Reflected XSS | Stored XSS |
| --- | --- | --- |
| Persistence | ❌ Temporary (in request) | ✅ Persistent (in database or backend) |
| Delivery Required | ✅ Yes – needs crafted link | ❌ No – user stumbles into it |
| Timing critical? | ✅ Yes – victim must be logged in *when clicking* | ❌ No – attack waits for victim |
| Works on all users? | ❌ Only if they click attacker's link | ✅ Yes, all users who load the data |
| Ideal for phishing, mass theft | ❌ Harder to scale | ✅ Easy to mass exploit |
🛠 Impact: What the Attacker Can Do
- **Session Hijacking**: Steal cookies and log in as victim
- 📬 **CSRF Automation**: Auto-submit forms, change emails/passwords
- 💳 **Perform Transactions**: Buy items, send messages, delete accounts
- 👁️ **Spy on Activity**: Log keystrokes, intercept inputs
- 🎣 **Phishing UI**: Replace parts of the page with fake forms
- 📦 **Drop Malware**: Inject malicious iframes or JS payloads



What is DOM XSS ?

> DOM-based XSS happens when JavaScript running in the browser:
> 
- Takes **user-controllable input** (like the URL)
- And inserts it **directly into the page** (into the DOM)
- **Without sanitizing or escaping it**

The JavaScript reads from location.search (the query string), and writes directly into .innerHTML — a classic source-to-sink path.

| Step | What Happens |
| --- | --- |
| 1. Input (source) | Attacker controls part of the URL: `?msg=...` |
| 2. JavaScript reads it | JS uses `location.search`, `location.hash`, etc. |
| 3. Data reaches a sink | Dangerous function like `.innerHTML`, `eval()` |
| 4. Execution | The browser executes the malicious code |
### Common **Sources** (where attacker input comes from):

- `location.search` (URL parameters)
- `location.hash` (after `#`)
- `document.referrer`
- `document.cookie`
- `localStorage`, `sessionStorage`

### 💥 Common **Sinks** (where dangerous execution happens):

- `innerHTML`
- `document.write()`
- `eval()`, `Function()`
- `setTimeout("string")`
- `location.href = userInput`
- `src`, `href`, `onload` attributes

| Feature | Reflected XSS | Stored XSS | DOM-based XSS |
| --- | --- | --- | --- |
| Happens where? | Server echoes request | Server stores + displays | In browser JavaScript |
| Needs server response? | ✅ Yes | ✅ Yes (later) | ❌ No |
| Found in source view? | ✅ Usually | ✅ Usually | ❌ No (use DevTools) |
| Exploited by? | Crafted link | Comment/post/profile | Bad JS handling |

| Principle | Why It Matters |
| --- | --- |
| `document.write()` is dangerous | It parses raw HTML directly into the page |
| `location.search` is attacker-controlled | Anything in the URL can be manipulated |
| Building HTML with unescaped input = 💣 | Allows attackers to inject scripts |
| Safer APIs exist | Use `createElement`, `textContent`, and `appendChild` |

DOM-based XSS via third-party libraries, specifically jQuery, which is a common real-world attack surface — especially when developers unknowingly pass attacker-controlled data into functions like .attr(), .html(), or .append().

| Step | Description |
| --- | --- |
| 🟡 Input Source | Attacker input in the **URL or form field** |
| 🟠 Server-side | Reflects the value into the HTML page |
| 🔵 DOM Access | Client-side JavaScript reads the value (e.g., from `innerHTML`, `value`, etc.) |
| 🔴 Sink | JavaScript executes or inserts the input into a **dangerous function**, like `eval`, `innerHTML`, `setTimeout`, etc. |
| 💥 XSS | Script executes in the victim's browser |

#### Testing in different XSS Context :

Context #1: Between HTML Tags (Text Content)
```bash
<p>Hello, [user input here]</p>
```

Payload	What It Does

| Payload | What It Does |
| --- | --- |
| `<script>alert(1)</script>` | Classic script injection |
| `<img src=x onerror=alert(1)>` | Self-closing tag with JS in attribute |
| `<svg onload=alert(1)>` | SVG tag with JS event |
| `<iframe src="javascript:alert(1)">` | Executes JS on load (browser-dependent) |
| `<math href="javascript:alert(1)">` | Obscure tag bypass |


| Trait | Explanation |
| --- | --- |
| ✅ Easy to exploit | Just inject a tag like `<script>` or `<img>` |
| 🔐 Escaping needed | Use `htmlspecialchars()` or output encoding |
| 🧪 Payload tip | Start with `<img src=x onerror=alert(1)>` — runs without user click |




Context #2 What is the “HTML Attribute Context”?

```bash
<input value="[INPUT]">
" autofocus onfocus=alert(1) x="
<input value="" autofocus onfocus=alert(1) x="">
" onclick=alert(1) x="

" autofocus onfocus=alert(1) x="
" onmouseover=alert(1) x="
" onpointerenter=alert(1) x="
" onanimationstart=alert(1) style=animation-name:foo x="
```


| Challenge | Workaround |
| --- | --- |
| Angle brackets (`<`, `>`) blocked | Don't inject new tags — use attributes only |
| Quotes escaped (`"`) | Use single quotes `'` or break early with unquoted attributes |
| WAF blocks `onerror`, `onload` | Use `onfocus`, `onpointerenter`, `ontoggle`, etc. |

Context #3 What is accesskey in HTML?