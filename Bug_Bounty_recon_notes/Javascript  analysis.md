
#### Automation JS recon:
Recon collecting js files:
```bash
For using single or main domain recon.

katana -u http://target.com -d 5 -jc -silent -o from_katana.txt
katana -u https://yourtarget.com -d 5 -jc -aff -fx -s depth-first -o katana-df.txt
katana -u https://yourtarget.com -d 5 -jc -aff -fx -s breadth-first -o katana-bf.txt
katana -u https://yourtarget.com  -d 5 -jc -c 50 -o katana_normal_scan

cat katana* | grep .js$ | sort -u | tee katana_js_yourtarget
cat from_katana.txt | grep '\.js' | sort -u > js_urls/from_katana_clean.txt

katana -u https://target.com -jc -o katana_raw.txt
cat katana_raw.txt | grep '\.js' | sort -u > js_files.txt

urlfinder -d yourtarget.com -all -o urlfinder_yourtarget
cat urlfinder_yourtarget | grep .js$ | sort -u | tee urlfinder_js_yourtarget



gau target.com | grep "\.js" | tee js_urls/from_gau.txt
gau target.com | grep '\.js' | httpx -mc 200 | tee js_urls/from_gau_live.txt

waybackurls target.com | grep "\.js" | tee js_urls/from_wayback.txt
echo https://yorutarget.com | waybackurls > wayback_yourtarget.txt
cat wayback_yourtarget.txt | grep .js$ |  sort -u | tee wayback_js_yourtarget




waymore -i yourtarget.com -oU waymore_yourtarget.txt
cat waymore_youraget.txt | grep .js$ | sort -u | tee waymore_js_yourtarget
cat katana_js_yourtarget urlfinder_js_yourtarget hakrawler_js_yourtarget wayback_js_yourtarget waymore_js_yourtarget | sort -u | tee final_js_yourtarget




echo https://ferrero.com | hakrawler -d 5 -subs  | grep '\.js' | tee js-files_hak.txt
echo https://yourtarget.com | hakrawler -d 5 -t 30 -subs -u | tee hakrawler_yourtarget 
cat hakrawler_yourtarget | grep .js$ | sort -u | tee hakrawler_js_yourtarget


echo "https://target.com" | meg --extensions js

last do this:
cat *.txt | grep '\.js' | sort -u > final_js.txt
cat *.txt | grep -E '\.js$|\.js\?' | tee jsurls.txt
cat final_js.txt | ~/go/bin/httpx -mc 200,301,302,403,401 -silent > live_js_urls.txt


For using subdomain:

cat live_subdomains.txt | while read domain; do   echo "[+] Crawling with Katana: $domain";   katana -u $domain -d 3 -jc -silent -o $(echo $domain | sed 's/https\?:\/\///').txt; done
cat *.txt | grep '\.js' | sort -u > js_urls/all_from_katana.txt


cat live_subdomains.txt | while read domain; do
  echo "[+] GAU: $domain"
  gau $domain | grep '\.js' >> all_from_gau.txt
done


cat live_subdomains.txt | while read domain; do
  echo "[+] Wayback: $domain"
  echo $domain | waybackurls | grep '\.js' >> js_urls/wayback/all_from_wayback.txt
done

cat live_subdomains.txt | while read domain; do   echo "[+] Hakrawler: $domain";   echo $domain | hakrawler -d 4 -subs | grep '\.js' >> all_from_hakrawler.txt; done

cat live_subdomains.txt | subjs | sort -u | tee js_files_from_subjs.txt
cat js_files_from_subjs.txt all_urls.txt | grep '\.js' | sort -u > final_js_urls.txt


Download all js file
cat all_js_urls.txt | while read url; do
    filename=$(basename $url | cut -d'?' -f1)
    wget -q --no-check-certificate -O js_files/$filename "$url"
done

cat final_js.txt | while read url; do
  curl -s -L "$url" >> js_files/all_javascript_dump.txt
done

mkdir js_files
cat js_files.txt | while read url; do
    filename=$(echo $url | awk -F/ '{print $(NF)}' | cut -d'?' -f1)
    curl -s $url -o js_files/$filename
done


mkdir js_files
cat live_js_urls.txt | while read url; do
  fname=$(echo $url | sed 's|https\?://||; s|[/?=&]|_|g')
  curl -s "$url" -o js_files/$fname.js
done




```


Enumeration :
```bash

Enumeration of downloaded file:
 grep -E -i -r "\b(aws_access_key|aws_secret_key|api[_-]?key|apikey|secret[_-]?key|access[_-]?key|secret|passwd|password|pwd|admin[_-]?pwd|db[_-]?pass|ftp[_-]?pass|jdbc|sql|mysql|postgres|db[_-]?url|mongodb|auth[_-]?token|jwt|bearer|firebase|swagger|heroku|slack[_-]?token|gcp|oauth[_-]?token|.env|ssh[_-]?key|htaccess|git[_-]?token|access[_-]?token|admin|config|json|token)" all_javascript_dump.txt


Enumeration of comments:
grep -oP '(?<=//).*' all_javascript_dump.txt
grep -oP '(?<!http:)(?<!https:)(?<=//).*' all_javascript_dump.txt
grep -oP '(?<=//).*' all_javascript_dump.txt | grep -vE '^http'
cat all_javascript_dump.txt | tr '\n' ' ' | grep -oP '/\*.*?\*/'
grep -oP '(?<=//).*' all_javascript_dump.txt | grep -vE '^http' > js_comments_singleline.txt
cat all_javascript_dump.txt | tr '\n' ' ' | grep -oP '/\*.*?\*/' > js_comments_multiline.txt
cat js_comments_singleline.txt js_comments_multiline.txt > all_js_comments.txt
cat all_js_comments.txt | grep -Ei '(todo|fixme|key|token|debug|auth|secret|pass|admin|config|url|api)'


Enumeration of sensitive informatoin
grep -irl "fetch(" js_files/ > fetch_based.txt
grep -irl "axios" js_files/ > axios_based.txt
grep -irl "token" js_files/ > tokens.txt
grep -irl "apiKey" js_files/ > apikeys.txt

grep -Pozr "https?:\/\/[^\s\"']+" js_files/ | sort -u > endpoints.txt
grep -irn 'Authorization' js_files/

grep -Eo 'AIza[0-9A-Za-z\\-_]{35}' js_files/* >> secrets.txt
grep -Eo 'sk_live_[0-9a-zA-Z]{24}' js_files/* >> secrets.txt
grep -Eo 'eyJ[a-zA-Z0-9-_=]+?\.[a-zA-Z0-9-_=]+\.?[a-zA-Z0-9-_.+/=]*' js_files/* >> jwt.txt

grep -iE "todo|fixme|bug|devNote|debug" js_files/*




Using secrefinder.py :
cat ~/js_recon/final_url_js.txt | while read url; do   echo "[+] Scanning: $url";   python3 SecretFinder.py -i "$url" -o cli; done

cat live_js_urls.txt | while read url; do
  echo "[+] Scanning: $url" | tee -a secretfinder_output.txt
  python3 SecretFinder.py -i "$url" -o cli -g 'jquery;bootstrap;cdn.jsdelivr.net' | tee -a secretfinder_output.txt
done

python3 SecretFinder.py -i "$url" -o cli -r 'apikey|secret|token|auth|password'

python3 SecretFinder.py -i "$url" -o cli -c 'sessionid=abc123' -H 'X-Custom-Header: test' -p http://127.0.0.1:8080

cat live_js_urls.txt | while read url; do
  echo "[+] Scanning: $url" | tee -a secretfinder_output.txt
  python3 SecretFinder.py -i "$url" -o cli -g 'jquery;bootstrap;cloudflare;cdn' -r 'apikey|token|secret|auth|config|firebase' | tee -a secretfinder_output.txt
done

Using mantra tool:
cat live_subdomains.txt  | while read domain; do
  echo "[+] Running Mantra on: $domain"
  mantra "$domain" -d -t 100 -ua "ReconBot" -ep "apiKey|token|secret|auth|pass|config"
done

Using lazyegg :
waybackurls vulnweb.com | grep '\.js$' | awk -F '?' '{print $1}' | sort -u | xargs -I{} bash -c 'python lazyegg.py "{}" --js_urls --domains --ips' > jsurls.log && cat jsurls.log | grep '\.' | sort -u

cat jsurls.txt | xargs -I{} bash -c 'echo -e "\ntarget : {}\n" && python lazyegg.py "{}" --js_urls --domains --ips --leaked_creds'

USing jsecret
cat ~/js_recon/live_js_urls.txt | while read url; do   echo "[+] Scanning: $url";   echo "$url" | ~/go/bin/jsecret; done




Without dowloading files and recon.
cat live_js_urls.txt | while read url; do
  echo "[+] $url"
  curl -s "$url" | grep -Ei '(api[_-]?key|secret|token|auth|bearer|firebase)'
done

cat all-urls.txt | grep -Ei "\\.js$" | grep -vE "jquery|bootstrap|analytics" | anew js-files.txt
cat js-files.txt | httpx -status-code -silent -mc 200 | anew live-js.txt
cat live-js.txt | while read url; do curl -s "$url" | grep -E "apiKey|auth|token|secret|key" --color=always; done


```






Manual Recon of  javascript files :

used below code for  bookmark add url here like this . click on this javascript when visit target.
```bash
javascript:(function(){const e=/(['"])(https?:\/\/[^'"]+?|\/[^'"]+?|[a-zA-Z0-9_\-\/\.]+\.(php|json|js|html|aspx|jsp|do|action|cgi)[^'"]*?)\1/g,t=new Set,o=document.documentElement.outerHTML.matchAll(e);for(const e of o)t.add(e[2]);const n=document.getElementsByTagName("script");for(let o=0;o<n.length;o++){const s=n[o].src;s&&fetch(s).then(e=>e.text()).then(n=>{const o=n.matchAll(e);for(const e of o)t.add(e[2])}).catch(e=>console.log("Fetch error: ",e))}setTimeout(()=>{const e=document.createElement("div");e.style="position:fixed;bottom:0;left:0;width:100%;max-height:300px;overflow:auto;background:#111;color:#0f0;padding:10px;z-index:9999;font-family:monospace;font-size:12px;border-top:2px solid #0f0";e.innerHTML="<b>💡 JS Recon Results:</b><br>";t.forEach(t=>{e.innerHTML+=t+"<br>"}),document.body.appendChild(e)},3e3);})();
```

copy and paste in console
```javascript
(function () {
  const regex = /(['"])(https?:\/\/[^'"]+?|\/[^'"]+?|[a-zA-Z0-9_\-\/\.]+?\.(php|json|js|html|aspx|jsp|do|action|cgi)[^'"]*?)\1/g;
  const results = new Set();

  // Scan HTML
  const pageContent = document.documentElement.outerHTML;
  const matchesInPage = pageContent.matchAll(regex);
  for (const match of matchesInPage) {
    results.add(match[2]);
  }

  // Scan all external JS
  const scripts = document.getElementsByTagName('script');
  for (let i = 0; i < scripts.length; i++) {
    const src = scripts[i].src;
    if (src) {
      fetch(src)
        .then(res => res.text())
        .then(js => {
          const matchesInJS = js.matchAll(regex);
          for (const match of matchesInJS) {
            results.add(match[2]);
          }
        })
        .catch(err => console.log('Fetch error: ', err));
    }
  }

  // Output after 3 seconds
  setTimeout(() => {
    console.log('[+] Discovered paths/endpoints from JS & HTML:');
    results.forEach(res => console.log(res));
  }, 3000);
})();
```


```bash
import requests
import re
from bs4 import BeautifulSoup

# Target URL
target = "https://target.com"

# Fetch page source
response = requests.get(target)
soup = BeautifulSoup(response.text, "html.parser")

# Extract JS files
js_files = set()
for script in soup.find_all("script"):
    if script.get("src"):
        js_url = script.get("src")
        if not js_url.startswith("http"):
            js_url = target + js_url
        js_files.add(js_url)

print("\n[+] Found JavaScript Files:")
for js in js_files:
    print(js)

# Extract endpoints from JS files
print("\n[+] Extracting endpoints from JavaScript:")
endpoints = set()
for js in js_files:
    try:
        js_response = requests.get(js)
        found_endpoints = re.findall(r'\/[a-zA-Z0-9_\-/]*', js_response.text)
        for ep in found_endpoints:
            endpoints.add(ep)
    except:
        pass

for endpoint in endpoints:
    print(endpoint)
```

https://medium.com/@cyphernova1337/how-i-used-the-js-map-file-to-gain-admin-access-e30e6f00adb7

JS Recon Pentest Guide:

1. **Sensitive Data**
   - Look for hard-coded credentials, API keys, tokens, and secrets.

2. **Hidden Hostnames/IPs/URLs**
   - Identify hidden endpoints, IP addresses, or URLs for backend connections.

3. **API Endpoints**
   - Find and test API endpoints for vulnerabilities.

4. **Comments**
   - Check for developer comments that may leak sensitive information or logic.

5. **Error Messages**
   - Analyze error handling and messages for information leakage.

6. **Function Names and Parameters**
   - Review for any suspicious or unusual function calls and parameters.

7. **Obfuscation**
   - Look for obfuscated code that might hide malicious activity.

**LocalStorage & Cookies**
- Ensure no sensitive information is stored in localStorage or cookies (e.g., passwords, tokens).
- Verify cookies have HttpOnly, Secure, and SameSite attributes set appropriately.
- Check the expiry date of cookies and the scope (domain/path) to minimize the attack surface.
- Assess for potential XSS vulnerabilities through stored values.
- Ensure that data stored is encrypted to prevent easy access if compromised.

**GraphQL**
- Ensure introspection is disabled in production to prevent schema leakage.
- Test for proper validation and sanitation of user inputs to avoid injection attacks.
- Verify proper authorization checks to prevent unauthorized data access.
- Implement and test for limits on query depth and complexity to avoid DoS attacks.
- Review error messages for information disclosure.

**MongoDB**
- Test for injection vulnerabilities where unvalidated user input can manipulate database queries.
- Ensure sensitive data is not stored in plaintext within the database.
- Check for insecure database configurations, such as open network access or weak authentication.
- Verify appropriate access controls to prevent unauthorized data access.

**JSON**
- Look for any sensitive information stored or transmitted in JSON format.
- Ensure JSON data is properly validated to prevent injection and other attacks.
- Verify JSON parsing libraries handle malformed or malicious JSON correctly to prevent crashes or exploits.
- Test APIs that consume or produce JSON for potential vulnerabilities, such as injection attacks or data leakage.

**Dangerous Functions**
- Look for usage of `eval()` as it can execute arbitrary code if input is not sanitized.
- Identify use of `innerHTML` and ensure input is properly sanitized to prevent XSS.
- Inspect for `document.write()` as it can create XSS vulnerabilities.
- Check for dynamic code execution with `setTimeout()`/`setInterval()` that can lead to injection vulnerabilities.
- Similar to `eval()`, the `Function()` constructor can execute arbitrary code.

**Additional Aspects**
- Review third-party libraries for known vulnerabilities and ensure they are up-to-date.
- Ensure a strong CSP is in place to mitigate XSS attacks.
- Check CORS policies to prevent unauthorized cross-origin access.
- Verify that HTTPS is enforced to secure data in transit.
- Ensure error logs do not expose sensitive information and are properly secured.

**Wordlist/Path Enumeration for Hidden JS Files**
- Use the LazyEgg tool for JS file enumeration and data extraction.
- Example Command:
  `python lazyegg.py <target_url> --js_scan -w wordlist.txt`
- Common Wordlist Entries:
  - admin.js
  - config.js
  - secrets.js
  - debug.js
  - api.js