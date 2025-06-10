

using paramspider :
```bash
python3 paramspider.py --domain hackerone.com 
python3 paramspider.py --domain hackerone.com --exclude php,jpg,svg
python3 paramspider.py --domain hackerone.com --level high
python3 paramspider.py --domain hackerone.com --exclude php,jpg --output hackerone.txt
python3 paramspider.py --domain hackerone.com --placeholder FUZZ2
python3 paramspider.py --domain hackerone.com --quiet
python3 paramspider.py --domain hackerone.com --subs False 
python3 paramspider.py --domain hackerone.com -l high -o params.txt -e js,png,jpg,gif,css
```

using gau:
```bash
gau example.com | grep '?' | sort -u > urls_with_params.txt
gau example.com | grep '?' | grep -E '\.php|\.asp|\.aspx' | sort -u > urls_with_params.txt
gau example.com | grep '?' | grep -vE '\.css|\.js|\.jpg|\.png|\.gif' | sort -u > urls_with_params.txt
gau example.com | grep '?' | grep -E 'id=|page=' | sort -u > urls_with_params.txt
gau example.com | grep '?' | grep '^https://' | sort -u > urls_with_params.txt
gau example.com | grep '?' | grep 'sub.example.com' | sort -u > urls_with_params.txt
gau example.com | grep '?' | grep -v '&' | sort -u > urls_with_params.txt
gau example.com | grep '^https://' | grep '?' | grep -v '&' | grep -vE '\.css|\.js|\.jpg|\.png|\.gif' | sort -u > filtered_urls_with_params.txt
```

using waybackurls:

```bash
waybackurls target.com | grep "=" | grep -vE '\.(jpg|png|css|js|woff|svg|gif|ttf|eot|ico)$'
cat subdomains.txt | httpx -mc 200 -silent | waybackurls | grep "=" > wayback_params.txt


cat params_from_wayback.txt | gf xss > xss.txt
cat params_from_wayback.txt | gf sqli > sqli.txt
cat wayback_output.txt | gf params > params.txt
cat wayback_output.txt | grep -Ei ‘token=|auth|apikey=|key=|secret’ > juicy.txt

```

using arjun :
using content discovery using waybackurl and gau
```bash
# 1. Enumerate endpoints
gau target.com | grep -vE '\.(css|png|jpg|svg|woff|eot)' > urls.txt

# 2. Scan each for hidden params
cat urls.txt | while read url; do
  arjun -u "$url" -m GET -t 10 -oT arjun_results.txt
done

# 3. Use qsreplace + xss payloads
cat arjun_results.txt | qsreplace '<script>alert(1)</script>' | while read u; do curl -s -L "$u"; done

```



using gf patterns for finding interesting parameter used paramspider gf profile or used other gf or create your own : 
```bash
cat params.txt  | gf sqli > sqli_param.txt
cat params.txt  | gf xss > xss_param.txt
cat params.txt  | gf lfi > lfi_param.txt
cat params.txt  | gf rce > rce_param.txt
cat params.txt  | gf idor > idor_param.txt
cat params.txt  | gf debug_logic > debug_logic.txt
cat params.txt  | gf redirect > redirect_param.txt
cat params.txt  | gf ssrf > ssrf_param.txt
cat params.txt  | gf ssti > idor_param.txt
cat params.txt  | gf interestingparams.txt > interestingparams.txt
cat params.txt  | gf img-traversal > img-traversal_param.txt
cat params.txt  | gf interestingEXT > interestingEXT_param.txt
cat params.txt  | gf interestingsubs > interestingsubs_param.txt
cat params.tx   | gf ssti > idor_param.txt
or more ...
```


```

```bash
fallparams -u https://target.com
fallparams -u https://target.com -c -d 3
fallparams -u https://target.com -hl
fallparams -dir /path/to/burp_export/
fallparams -r raw_request.txt
fallparams -u https://target.com -H "Authorization: Bearer <token>" -H "User-Agent: ReconBot"
fallparams -u https://target.com/api -X POST -b 'id=1'
fallparams -u https://target.com -nl 3 -xl 25

fallparams -u https://target.com -c -hl -d 3 -o params.txt && \
cat params.txt | grep "=" | gf xss > xss.txt

cat urls.txt | while read url; do fallparams -u "$url" -c -hl -d 3 -silent -o "$(echo $url | sed 's|https\?://||; s|[/?=&]|_|g')_fallparams.txt"; done


cat domains.txt | while read domain; do python3 paramspider.py -d "$domain" -o "$(echo $domain)_params.txt" -l high -q -e png,jpg,css,js; done

cat domains.txt | while read domain; do waybackurls "$domain" | grep "=" | tee "${domain}_wayback.txt"; done


```