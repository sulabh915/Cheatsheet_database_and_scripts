
Collecting urls :
```bash
cat subs.txt | gau --threads 50 > gau-raw.txt
cat subs.txt | waybackurls > wayback-raw.txt
cat gau-raw.txt wayback-raw.txt | anew all-urls.txt
```

looking for vulnerable  files :
```bash
cat all-urls.txt | grep -Ei "\\.(php|aspx|jsp|bak|env|git|json|config|sql|log)$" | anew filtered-files.txt
cat all-urls.txt | grep "\\?" | anew urls-with-params.txt
cat all-urls.txt | grep -Ei "\\.js$" | grep -vE "jquery|bootstrap|analytics" | anew js-files.txt
cat js-files.txt | httpx -status-code -silent -mc 200 | anew live-js.txt

dirsearch -u https://example.com -e php,cgi,htm,html,shtm,shtml,js,txt,bak,zip,old,conf,log,pl,asp,aspx,jsp,sql,db,sqlite,mdb,tar,gz,7z,rar,json,xml,yml,yaml,ini,java,py,rb,php3,php4,php5 --random-agent --recursive -R 3 -t 20 --exclude-status=404 --follow-redirects --delay=0.1

cat allurls.txt | grep -E "\.xls|\.xml|\.xlsx|\.json|\.pdf|\.sql|\.doc|\.docx|\.pptx|\.txt|\.zip|\.tar\.gz|\.tgz|\.bak|\.7z|\.rar|\.log|\.cache|\.secret|\.db|\.backup|\.yml|\.gz|\.config|\.csv|\.yaml|\.md|\.md5"
cat allurls.txt | grep -E "\.(xls|xml|xlsx|json|pdf|sql|doc|docx|pptx|txt|zip|tar\.gz|tgz|bak|7z|rar|log|cache|secret|db|backup|yml|gz|config|csv|yaml|md|md5|tar|xz|7zip|p12|pem|key|crt|csr|sh|pl|py|java|class|jar|war|ear|sqlitedb|sqlite3|dbf|db3|accdb|mdb|sqlcipher|gitignore|env|ini|conf|properties|plist|cfg)$"

site:*.example.com (ext:doc OR ext:docx OR ext:odt OR ext:pdf OR ext:rtf OR ext:ppt OR ext:pptx OR ext:csv OR ext:xls OR ext:xlsx OR ext:txt OR ext:xml OR ext:json OR ext:zip OR ext:rar OR ext:md OR ext:log OR ext:bak OR ext:conf OR ext:sql)

<<<<<<< HEAD
```
=======

gobuster dir -u https://example.com -w /usr/share/wordlists/dirb/common.txt -x php,html,txt,bak,old

gau target.com | grep -iE "\.git|\.env|\.log|\.sql"
ffuf -u https://target.com/FUZZ -w dev_files.txt -t 100

```

Common files to enumerate :
```bash
curl https://example.com/robots.txt
curl https://example.com/sitemap.xml
curl https://example.com/.env
curl https://example.com/.git/config
curl https://example.com/.DS_Store
```



Enumerate .git:
```bash
./gitdumper.sh https://example.com/.git/ /tmp/site-git/
cd /tmp/site-git/
git log

cat domains.txt | grep "SUCCESS" | gf urls | httpx-toolkit -sc -server -cl -path "/.git/" -mc 200 -location -ms "Index of" -probe
```

Using Trace :
```bash
curl -X TRACE -i https://example.com
```

Backupfinder :
```bash
git clone https://github.com/anmolksachan/WayBackupFinder.git
cd WayBackupFinder
python3 wayBackupFinder.py


#backup filename generator:
https://github.com/Nishantbhagat57/backup-gen
```

using nuclei tool:
```bash
nuclei -u https://target.com -t exposures/ -v
	nuclei -l live_subs.txt -t cves/ -o bugs_found.txt  

nuclei -l resolved.txt -t ~/nuclei-templates/ -etags cloud,misconfig -severity critical,high -stats

```

using nikto:
```bash
nikto -h https://target.com
```

using github recon :
```bash
filename:.env DB_PASSWORD site:github.com
cat targets.txt | waybackurls | grep -Ei "\.(env|sql|log|bak)$"
```

using time machine :
```bash 
python3 thetimemachine.py example.com --backups
python3 thetimemachine.py example.com --listings
python3 thetimemachine.py example.com --jwt
```

HTML content filtering :
```bash
echo domain | gau | grep -Eo '(\/[^\/]+)\.(php|asp|aspx|jsp|jsf|cfm|pl|perl|cgi|htm|html)$' | httpx -status-code -mc 200 -content-type | grep -E 'text/html|application/xhtml+xml'
```


Headers recon :
```bash
#!/usr/bin/env python3
import requests, sys

# Headers we want to extract
INTERESTING_HEADERS = [
    "Content-Security-Policy",
    "Access-Control-Allow-Origin",
    "X-Frame-Options",
    "Strict-Transport-Security",
    "X-Content-Type-Options",
    "Referrer-Policy",
    "Permissions-Policy",
    "Server"
]

def fetch_headers(url):
    """Fetch security headers from a URL"""
    try:
        resp = requests.get(url, timeout=5, allow_redirects=True)
        found = {}
        for h in INTERESTING_HEADERS:
            if h in resp.headers:
                found[h] = resp.headers[h]
        return found
    except requests.exceptions.RequestException as e:
        print(f"[!] Error fetching {url}: {e}")
        return {}

def main(input_file):
    with open(input_file, "r") as f:
        urls = [line.strip() for line in f.readlines()]

    with open("security_headers_report.txt", "w") as report:
        for url in urls:
            if not url.startswith("http"):
                url = "https://" + url  # default https
            print(f"[+] Checking headers for: {url}")
            headers = fetch_headers(url)

            report.write(f"\n=== {url} ===\n")
            if headers:
                for k, v in headers.items():
                    print(f"  {k}: {v}")
                    report.write(f"{k}: {v}\n")
            else:
                print("  [!] No interesting headers found!")
                report.write("[!] No interesting headers found!\n")

    print("\n✅ Done! Results saved in security_headers_report.txt")

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print(f"Usage: {sys.argv[0]} <file_with_urls_or_subdomains>")
        sys.exit(1)
    main(sys.argv[1])

```

https://securityheaders.com/

```bash
wpscan --url https://site.com -disable-tls-checks -api-token <here> -e at -e ape u enumerate
ap-plugins-detection aggressive
-force
```