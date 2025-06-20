
Identify waf technology:
```bash
sudo nmap bugcrowd.com -p80,443 --script=http-waf-fingerprint -v
sudo nmap bugcrowd.com -p80,443 --script=http-waf-detect -v
wafw00f hackerone.com
```

Common WAF Detection Methods: 

```

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

✅ Custom Rules (ModSecurity / NAXSI / AWS WAF)
    Self-defined rulesets like:
        Block eval( in any input
        Allow POST only to /submit
```

```bash
🧪 WAF Detection Tips (as an attacker or tester):
     Do curl or ffuf requests fail but browsers work? → Header/JS check
     Do aggressive scans get blocked after N requests? → Rate limiting
     Are cloud IPs blocked but home IP works? → Geo/ASN filter
     Does the site issue JS-based redirects? → Challenge verification
     Is 403 returned on POST to /login with missing CSRF? → Token enforcement
```

