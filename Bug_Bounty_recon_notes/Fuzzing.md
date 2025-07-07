
Using ffuf :

Basic fuzz:
```bash
ffuf -u https://target.com/FUZZ -w wordlist.txt #Basic URL fuzzing at the endpoint.
```

Change header:
```
ffuf -u https://target.com/admin/FUZZ -w wordlist.txt \
-H "User-Agent: Mozilla/5.0" \
-H "X-Api-Key: 12345"
```

Add cookie:
```bash
ffuf -u https://target.com/dashboard/FUZZ -w wordlist.txt \
-b "auth=token123; csrftoken=abc123" 
#Useful for authenticated fuzzing (session/csrf cookies).
```

Send post data :
```bash
ffuf -u https://target.com/login -X POST \
-d "username=admin&password=FUZZ" \
-w passwords.txt \
-H "Content-Type: application/x-www-form-urlencoded"
```

protocol change:
```bash
ffuf -u https://target.com/FUZZ -w paths.txt -http2 
# Can bypass WAFs that don't inspect HTTP/2.
```

Do Not Download Response Body:
```bash
ffuf -u https://target.com/FUZZ -w wordlist.txt -ignore-body #Speeds up scans, useful for 403/404 header-only checks.
```

Follow redirect :
```bash
ffuf -u https://target.com/FUZZ -w dirs.txt -r
```

Do Not Encode URI:
```bash
ffuf -u https://target.com/page.php?id=FUZZ -w payloads.txt -raw #Use when sending pre-encoded payloads (%2e, %00, etc.).
```

Use Client Certificate + Key (mTLS):
```bash
ffuf -u https://secure.target.com/FUZZ -w wordlist.txt \
-cc client.crt -ck client.key
```

Set Request Timeout:
```bash
ffuf -u https://target.com/FUZZ -w wordlist.txt -timeout 5 #Set to 5 seconds per request (default is 10s).
```

Proxy Through Burp/ZAP:
```bash
ffuf -u https://target.com/FUZZ -w wordlist.txt -x http://127.0.0.1:8080 #nspect requests in Burp or test through TOR (socks5://).
```

Enable Recursion:
```bash
ffuf -u https://target.com/FUZZ -w dirs.txt -recursion
```

Set Depth Limit:
```bash
ffuf -u https://target.com/FUZZ -w dirs.txt -recursion -recursion-depth 2
```

Strategy Type:
```bash
ffuf -u https://target.com/FUZZ -w dirs.txt -recursion -recursion-strategy greedy
#greedy: recurse all matches
#default: only if redirection found
```


Auto-Calibration in ffuf:
```bash
ffuf -u https://target.com/FUZZ -w wordlist.txt -ac
#Sends default "junk" payloads (like this_should_not_exist) to see what "false" looks like.
#Then filters responses that look the same.
# Ideal for CMS or web servers that return 200 on every path.


ffuf -u https://target.com/FUZZ -w wordlist.txt -acc fake123 -acc random_zzz
# Replaces FUZZ with your custom junk payloads instead of using random ones.
#Allows better tuning when you know the server behavior.
#Use when default payloads don't trigger the correct 404/403 behavior.

ffuf -u https://api1.target.com/FUZZ -w wordlist.txt -ach
#If scanning multiple hosts in one wordlist or run, calibrates each host separately.
#Ensures accurate filtering for each target.

```


load your own config:
```bash
ffuf -config recon.yaml #Use when you have saved headers, rate limits, or filters in a YAML file for reuse.
```

output to json for other automation :
```bash
ffuf -u https://target.com/FUZZ -w paths.txt -json > results.json
#Perfect for piping into jq, grep, or security automation tools.
```

 Limit Total Scan Duration
```bash
ffuf -u https://target.com/FUZZ -w biglist.txt -maxtime 300
#Stops the entire fuzzing session after 5 minutes. Great for CI/CD pipelines.
```

 Add Delay or Randomize It:
 ```bash
 ffuf -u https://target.com/FUZZ -w wordlist.txt -p 0.5
ffuf -u https://target.com/FUZZ -w wordlist.txt -p 0.1-2.0
#Adds delay between requests to evade WAFs and avoid IP blocks.
```

Control Request Speed:
```bash
ffuf -u https://target.com/FUZZ -w wordlist.txt -rate 100
#Send max 100 requests/sec. Good for testing large scopes without being banned.
```

-sa — Stop on Any Error:
```bash
ffuf -u https://target.com/FUZZ -w endpoints.txt -sa
```

Stop if >95% of Responses are 403:
```bash
ffuf -u https://target.com/FUZZ -w paths.txt -sf
# Useful for WAF detection or skipping blocked endpoints.
```

Replay/Review a Previous Scan:
```bash
ffuf -search 80c3d15e
```

Thread:
```bash
ffuf -u https://target.com/FUZZ -w dirs.txt -t 100
# Control concurrency (40 by default). Increase to speed up or lower to avoid bans.
```

verbose :
```bash
ffuf -u https://target.com/FUZZ -w fuzzlist.txt -v
```

With Named Keyword:
```bash
ffuf -u https://target.com/page?user=NAME&role=ROLE -w names.txt:NAME -w roles.txt:ROLE
```

wordlist mode :
```bash
ffuf -u https://target.com/page?user=NAME&role=ROLE -w users.txt:NAME -w roles.txt:ROLE -mode clusterbomb
```

Add Extension :
```bash
ffuf -w wordlist.txt -u https://www.dehaninsurance.com/yahoo_site_admin/
credentials/FFUF -e .aspx, .html, .php, .txt, . conf, .bak
```

DirSearch Compatibility (with -e):
```bash
ffuf -u https://target.com/FUZZ -w wordlist.txt -e .php -D
```

Request fuzz:
```bash
ffuf -request req. txt -request-proto https -mode clusterbomb -w
users.txt: HFUZZ -w users. txt: WFUZZ -c -mc all -fl 7
```

input-cmd and input-shell:
```bash
ffuf -u https://target.com/FUZZ -input-cmd "cat dynamic.txt" -input-num 100
ffuf -u https://target.com/FUZZ -input-cmd "seq 1 1000" -input-num 1000 -input-shell /bin/zsh
```



```bash
ffuf -w vhosts.txt -u https://target.com -H "Host: FUZZ.target.com" -e .dev,.test,.bak -mc 200
```

usefull wordlist :
```bash
https://wordlists.assetnote.io/
https://github.com/fuzzdb-project/fuzzdb
https://github.com/reewardius/bbFuzzing.txt
```


wfuzz:

Subdomain:
```bash
wfuzz -c -Z -w subdomains.txt http://FUZZ.vulnweb.com
```

Directory fuzzing:
```bash
wfuzz -w wordlist/general/common.txt http://testphp.vulnweb.com/FUZZ

–hc/sc CODE #Hide/Show by code in response
–hl/sl NUM #ide/Show by number of lines in response
–hw/sw NUM #ide/Show by number of words in response
–hc/sc NUM #ide/Show by number of chars in response

wfuzz -w wordlist/general/common.txt --sc 200,301 http://testphp.vulnweb.com/FUZZ

```

