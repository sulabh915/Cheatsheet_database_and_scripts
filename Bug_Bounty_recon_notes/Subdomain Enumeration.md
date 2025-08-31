	
SUBDOMAIN ENUMERATION ALL POSSIBLE COMBINATION:

dig:
```bash
dig A example.com +short
dig AAAA example.com +short
dig MX example.com +short
dig TXT example.com +short
dig CNAME example.com +short
dig NS example.com +short
dig SOA example.com +short


#!/bin/bash

# Check if domain is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <domain>"
  exit 1
fi

DOMAIN=$1

echo "🔍 Checking DNS records for: $DOMAIN"
echo

# List of record types
for TYPE in A AAAA MX TXT CNAME NS SOA; do
  echo "➡️ $TYPE Record:"
  dig "$TYPE" "$DOMAIN" +short
  echo
done



dig axfr @target.com
dig AXFR example.com @ns1.example.com
	host -l example.com ns1.example.com

nslookup
> server ns1.example.com
> ls -d example.com



#Used in CTF:
#if box running dns server.
dig axfr @<BOX IP ADDRSSS>  <Domain name of box like "matrix.htb">
```

Sublist3r all possible combination :
```
sublist3r -d target.com
sublist3r -d target.com -v
sublist3r -d target.com -t 50
sublist3r -d target.com -o target_subs.txt
sublist3r -d target.com -e google,yahoo,baidu
sublist3r -d target.com -b
sublist3r -d target.com -t 50 -b -o all_found.txt
```

Subfinder :
```

subfinder -d target.com -recursive
subfinder -d target.com -all -r resolvers.txt
subfinder -dL domains.txt -all -recursive\
subfinder -d example.com -all -recursive -o subfinder.txt
subfinder -d target.com --all --recursive --silent | httpx -sc -td

```

> [!/usr/share/wordlists/seclists/Miscellaneous ] Wordlist for resolver
> 
>	/usr/share/wordlists/seclists/Miscellaneous/dns-resolvers.txt

Amass :
```
amass enum -d target.com
amass enum -df domains.txt
amass enum -d target.com -v -o subs.txt
amass enum -d target.com -brute
amass enum -d target.com -brute -active
amass enum -d target.com -brute -min-for-recursive 3
amass enum -d target.com -oA output/amass_scan
amass enum -d example.com --include-favicon
amass enum -d target.com -ip -src
amass enum -d target.com -silent
amass enum -d target.com -r resolvers.txt
amass enum -d target.com -config ~/.config/amass/config.ini
amass enum -asn 13335
amass enum -cidr 104.16.0.0/12
grep -oP '\b(?:[a-zA-Z0-9-]+\.)+nokia\.com\b' amass_nokia.txt | grep -v '^nokia\.com$' | sort -u | tr -d 92 > amass_nokia.txt 


amass enum -d target.com -brute -active -min-for-recursive 3 -oA fullscan -r /usr/share/wordlists/seclists/Miscellaneous/dns-resolvers.txt

amass enum -passive -d example.com | cut -d']' -f 2 | awk '{print $1}' | sort -u > amass.txt 
amass enum -active -d example.com | cut -d']' -f 2 | awk '{print $1}' | sort -u > amass.txt

```


Atldns :
```altdns -i known.txt -o permutations.txt -w words.txt
altdns -i known.txt -o permutations.txt -w words.txt -r -s resolved.txt
altdns -i known.txt -o alt_out.txt -w custom_words.txt

subfinder -d target.com -silent > known.txt
altdns -i known.txt -o alt_subs.txt -w words.txt -r -s resolved.txt

altdns -i all_subs.txt -o alt_out.txt -w /usr/share/wordlists/seclists/Discovery/DNS/subdomains-top1million-5000.txt

altdns -i subdomains.txt -o -w words.txt | dnsx -a -r resolvers.txt -o resolved_subdomains.txt

altdns -i all-passive-subdomains.txt -o permutations.txt -w words.txt


```


Alterx :
```bash
subfinder -d domain.com | alterx | dnsx
echo doamin.com | alterx -enrich | dnsx 
echo doamin.com | alterx -pp word=/usr/share/seclists/Discovery/DNS/subdomains-top1million-5000.txt | dnsx
```


assetfinder:
```bash
assetfinder subs-only target.com
assetfinder --subs-only example.com > assetfinder.txt
```


dnscan(bruteforce):
```bash
python3 dnscan.py -d example.com -w wordlist.txt
python3 dnscan.py -d example.com -w words.txt -r -m 3  #Recursive Subdomain Brute
python3 dnscan.py -d example.com -z  #check zonetransfer only
python3 dnscan.py -d example.com -w words.txt -a
python3 dnscan.py -d example.com -w words.txt -L resolvers.txt
python3 dnscan.py -d example.com -w words.txt --recurse-wildcards -r
python3 dnscan.py -d dev-%%.example.com -w words.txt


python3 dnscan.py -d target.com -w words.txt -o raw.txt
cat raw.txt | cut -d ' ' -f1 | dnsx -silent -r resolvers.txt -o alive.txt

(bruforce suing ffuf)
ffuf -u "https://FUZZ.target.com" -w wordlist.txt -mc 200,301,302
```

dnsshuffle:
```bash
shuffledns --mode bruteforce -d example.com -w words.txt -r resolvers.txt` | Brute-force subdomains 
shuffledns --mode resolve -l list.txt -r resolvers.txt` | Resolve known subs |
shuffledns --mode filter -ri massdns.txt` | Filter massdns output |
shuffledns --mode bruteforce -d example.com -w words.txt -r resolvers.txt -tr trusted.txt -sw
shuffledns --mode bruteforce -d example.com -w words.txt -r resolvers.txt -t 20000 --retries 2
shuffledns --mode bruteforce -d example.com -w words.txt -r resolvers.txt -tr trusted.txt -sw


```


puredns: fastest resolver tool  , used with permutation and bruteforce.
```bash
# Brute-force from wordlist directly
puredns bruteforce words.txt example.com --resolvers resolvers.txt > subs.txt

# Then resolve & clean
puredns resolve subs.txt --resolvers resolvers.txt > final_subs.txt
rj
su*bfinder -silent -d hackerone.com | dnsx -silent

cat subdomains.txt | dnsgen - > permutations.txt
74
puredns resolve permutations.txt ;-r resolvers.txt --wildcard-tests 10 --threads 50 -o valid.txt


```


github-subdomains
```bash
github-subdomains -d example.com -t ghp_xxx
github-subdomains -d example.com -t ghp_aaa,ghp_bbb,ghp_ccc
github-subdomains -d example.com -t ~/.github_tokens.txt
github-subdomains -d example.com -t ghp_xxx -o github_subs.txt
github-subdomains -d example.com -t ghp_xxx -e
github-subdomains -d example.com -t ghp_xxx -q
github-subdomains -d example.com -t ghp_xxx -raw
github-subdomains -d example.com -t ghp_xxx -k
github-subdomains -d domain.com -t [github_token]

github-subdomains -d example.com -t ~/.tokens -e -raw -o sub_target.txt


# Step 1: Extract subdomains from GitHub
github-subdomains -d target.com -t ~/.tokens -o gh-subs.txt

# Step 2: Clean, resolve, and probe
cat gh-subs.txt | dnsx -silent -o resolved.txt
cat resolved.txt | httpx -silent -title -status-code -o live-assets.txt

generate api token from developer setting from github.
```


using some public sources :
```bash
curl -s https://crt.sh\?q\=\domain.com\&output\=json | jq -r '.[].name_value' | grep -Po '(\w+\.\w+\.\w+)$' >crtsh.txt
curl -s "https://crt.sh/?q=%25.target.com&output=json" | jq -r '.[].name_value' | anew subs.txt  
curl -s "http://web.archive.org/cdx/search/cdx?url=*.hackerone.com/*&output=text&fl=original&collapse=urlkey" |sort| sed -e 's_https*://__' -e "s/\/.*//" -e 's/:.*//' -e 's/^www\.//' | sort -u > wayback.txt
	curl -s "https://www.virustotal.com/vtapi/v2/domain/report?apikey=[api-key]&domain=www.nasa.gov" | jq -r '.domain_siblings[]' | >virustotal.txt
```



vhost enumeration :
```bash
ffuf -w wordlist.txt -u https://target.com -H "Host: FUZZ.target.com"
cat subdomains.txt | httpx -title -web-server -ip -status-code -o vhosts.txt


https://www.ipneighbour.com/#/lookup/feedingindia.org
https://www.yougetsignal.com/tools/web-sites-on-web-server/
```


using discover hosts via IP Address and ASN Mapping
```bash
asnmap -d domain.com | dnsx -silent -resp-only
amass intel -org "nasa"
amass intel -active -cidr 159.69.129.82/32
amass intel -active -asn [asnno]
```

Harvesting IP Addresses Linked to Domains:
```bash
curl -s "https://www.virustotal.com/vtapi/v2/domain/report?domain=<DOMAIN>&apikey=[api-key]" | jq -r '.. | .ip_address? // empty' | grep -Eo '([0-9]{1,3}\.){3}[0-9]{1,3}'
curl -s "https://otx.alienvault.com/api/v1/indicators/hostname/<DOMAIN>/url_list?limit=500&page=1" | jq -r '.url_list[]?.result?.urlworker?.ip // empty' | grep -Eo '([0-9]{1,3}\.){3}[0-9]{1,3}'
curl -s "https://urlscan.io/api/v1/search/?q=domain:<DOMAIN>&size=10000" | jq -r '.results[]?.page?.ip // empty' | grep -Eo '([0-9]{1,3}\.){3}[0-9]{1,3}'
cat domains.txt | cut -d']' -f2 | awk '{print $2}' | tr ',' '\n' | sort -u > amass.txt
grep -oE "\b([0-9]{1,3}\.){3}[0-9]{1,3}\b"
shodan search Ssl.cert.subject.CN:"<DOMAIN>" 200 --fields ip_str | httpx-toolkit -sc -title -server -td

curl -s "https://urlscan.io/api/v1/search/?q=domain:gov.au&size=10000" | jq -r '.results[]?.page?.ip // empty' | grep -Eo '([0-9]{1,3}\.){3}[0-9]{1,3}' | dnsx -ptr -resp-only  | grep "gov.au" | tee urlscan.txt
```


using content security policy headers :
```bash
#!/usr/bin/env python3
import requests, re, sys

def extract_csp_domains(csp):
    """Extract domains/subdomains from CSP header"""
    return re.findall(r"(?:https?:\/\/)?([\w\.\-\*]+\.[a-zA-Z]{2,})", csp)

def fetch_csp(url):
    """Fetch CSP header from a given URL"""
    try:
        resp = requests.get(url, timeout=5, allow_redirects=True)
        csp = resp.headers.get("Content-Security-Policy")
        if csp:
            return extract_csp_domains(csp)
    except Exception as e:
        print(f"[!] Error fetching {url}: {e}")
    return []

def main(input_file):
    all_csp_subdomains = set()

    with open(input_file, 'r') as f:
        urls = [line.strip() for line in f.readlines()]

    for url in urls:
        # If it's just a bare domain, add https:// by default
        if not url.startswith("http"):
            url = "https://" + url

        print(f"[+] Checking CSP: {url}")
        subs = fetch_csp(url)
        for s in subs:
            all_csp_subdomains.add(s)

    # Save unique CSP subdomains
    with open("csp_subdomains.txt", "w") as out:
        for s in sorted(all_csp_subdomains):
            out.write(s + "\n")

    print(f"\n✅ Found {len(all_csp_subdomains)} unique subdomains inside CSP headers!")
    print("  -> Saved to csp_subdomains.txt")

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print(f"Usage: {sys.argv[0]} <file_with_live_urls_or_subdomains>")
        sys.exit(1)
    main(sys.argv[1])


#After run this commands
dnsx -l csp_subdomains.txt -resp -silent | httpx -silent -o alive_from_csp.txt
subjack -w csp_subdomains.txt -ssl -a -timeout 10 -o takeover_results.txt

```


using time machine :
```bash
python3 thetimemachine.py --subdomains
```



Combine , sort and unique :
```bash
cat file1.txt file2.txt file3.txt > combined.txt 
sort combined.txt | uniq > sorted_unique.txt
cat file1.txt file2.txt file3.txt | sort | anew finalnokia.txt 
cat file1.txt file2.txt file3.txt | sort -u > finalnokia.txt
cat file1 file2 | sort -u | tee final.txt
cat *.txt | sort -u > final.txt
```



Finding the live domain from list:
```bash
cat subdomains.txt | httpx -silent -o live_hosts.txt
httpx -l subdomains.txt -sc -title -web-server -ip -o detailed_hosts.txt
httpx -l subdomains.txt -td -o tech_detection.json -json
httpx -l subdomains.txt -favicon -o favicon_hashes.txt
httpx -l subdomains.txt -hash -o response_hashes.txt
httpx -l subdomains.txt -mc 200,403,500 -o filtered_hosts.txt
httpx -l subdomains.txt -mr "admin" -o admin_panels.txt

sed 's|https\?://||' live_hosts.txt > clean_hosts.txt


cat recon/example/domains.txt | httprobe
cat subexample.com.txt | httpx-toolkit -ports 80,443,8080,8000,8888 -threads 200 > subexample.coms_alive.txt
cat subdomain.txt | httpx-toolkit -ports 80,443,8080,8000,8888 -threads 200 > subdomains_alive.txt
```


Search using favicon hash:

upload the favicon to this site :
https://www.zoomeye.ai/?q=aWNvbmhhc2g9IjE1OTNmMTQ2NTBlNGIzOTM0ZDJhNmI0NmQ4NDRlOTA2Ig%3D%3D

```bash
python3 favicon-hashtrick.py -u "https://www.example.com/../favicon.ico" -k <shodan api>

	```
```bash
http.favicon.hash:<hash>
```

using Findomaion:
```bash
findomain -t target.com | tee findomain.txt
```


Massdns
```bash
massdns -r resolvers.txt -t A -o S -w resolved.txt domains.txt
```


using js file :
```bash
katana -u "https://target.com" -d 2 -o js-files.txt
grep -Eo "https?://[a-zA-Z0-9./?=_-]*" js-files.txt | grep target.com
python3 linkfinder.py -i js-files.txt -o output.html
```


Subdomain Takeover :
check for subdomain takeover
```bash
nuclei -l testlafinal.txt -t /root/subdomain-takeover.yml -vv
./subzy run --targets list.txt
whois 192.30.252.153 | grep "OrgName" #check for ip addresss orgname
./subjack -w subdomains.txt -t 100 -timeout 30 -o results.txt -ssl
https://github.com/anshumanbh/tko-subs
https://github.com/mhmdiaa/second-order
https://github.com/punk-security/dnsReaper

subzy run --targets subdomains.txt --concurrency 100 --hide_fails --verify_ssl

dig a "*.shopify.com"
```
https://github.com/EdOverflow/can-i-take-over-xyz
Take any fingerprint from 'can-i-take-over-xyz' and search on shodan or censys for subdomain takeover.


##### Screenshoting :
```bash
gowitness scan file -f live.txt --write-db --write-jsonl --write-csv --screenshot-format png --screenshot-fullpage --threads 15 

gowitness scan cidr -c 10.10.1.0/24 --threads 20 --write-db
gowitness scan nmap -f ./nmap.xml --port 443 --screenshot-fullpage

cat hosts.txt | aquatone
cat hosts.txt | aquatone -ports 80,443,8000,8080,8443
cat hosts.txt | aquatone -ports 80,81,443,591,2082,2087,2095,2096,3000,8000,8001,8008,8080,8083,8443,8834,8888

```

