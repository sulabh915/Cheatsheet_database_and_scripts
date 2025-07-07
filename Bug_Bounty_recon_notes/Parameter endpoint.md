

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
cat all-urls.txt | grep "\\?" | anew urls-with-params.txt
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
gf xss < urls-with-params.txt > xss.txt
gf ssti < urls-with-params.txt > ssti.txt
gf redirect < urls-with-params.txt > redirect.txt
or more ...

https://github.com/thecybertix/GF-Patterns/blob/main/gf.sh
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

using gospider :
```bash
gospider -s https://target.com -d 2 -t 10 --js --quiet | tee gospider_output.txt

#always look for name parameter and id parameter in html page where the mostly hidden input 
cat gospider_output.txt | grep -Eo 'name="[^"]+"|id="[^"]+"' | sort -u > attribute_params.txt
```

using javascript recon variable parameter:
```bash
mkdir js_files
cat live_js_urls.txt | while read url; do
  fname=$(echo $url | sed 's|https\?://||; s|[/?=&]|_|g')
  curl -s "$url" -o js_files/$fname.js
done

grep -rhoE 'var\s+[a-zA-Z0-9_]+|let\s+[a-zA-Z0-9_]+|const\s+[a-zA-Z0-9_]+' js_files/ | awk '{print $2}' | sort -u > variables.txt

grep -rhoE 'function\s+[a-zA-Z0-9_]+\s*\(([a-zA-Z0-9_,\s]*)\)' js_files/ | sed -E 's/.*\((.*)\).*/\1/' | tr ',' '\n' | sed 's/^[ \t]*//' | sort -u >> variables.txt

grep -rhoE '[a-zA-Z0-9_]+(?=\s*:)' js_files/ | sort -u >> variables.txt

cat variables.txt | sed '/^$/d' | sed 's/[^a-zA-Z0-9_]//g' | sort -u > potential_params.txt

cat potential_params.txt | while read param; do
  curl -s "https://target.com/?$param=test" | grep "$param"
done

ffuf -u "https://target.com/page?FUZZ=value" -w potential_params.txt -mc 200



cat potential_params.txt | while read param; do
  curl -X POST https://api.target.com/submit \
  -H "Content-Type: application/json" \
  -d "{\"$param\":\"test\"}"
done


```



```bash
#!/bin/bash

URLS="urls.txt"
PARAMS="potential_params.txt"
OUT_DIR="param_fuzz_results"
mkdir -p "$OUT_DIR"

echo "[*] Starting parameter fuzzing..."

while read url; do
  clean_url=$(echo "$url" | sed 's|https\?://||; s|[/?=&]|_|g')

  while read param; do

    # --- GET Request ---
    get_url="$url?$param=test"
    get_out="$OUT_DIR/${clean_url}_GET.txt"

    echo "[GET] $get_url" >> "$get_out"
    curl -s "$get_url" | grep -i "$param" >> "$get_out"

    # --- POST Request ---
    post_out="$OUT_DIR/${clean_url}_POST.txt"
    echo "[POST] $param=test to $url" >> "$post_out"
    curl -s -X POST "$url" -H "Content-Type: application/x-www-form-urlencoded" \
         -d "$param=test" | grep -i "$param" >> "$post_out"

  done < "$PARAMS"
done < "$URLS"

echo "[✔] Fuzzing complete. Results saved in $OUT_DIR/"

```