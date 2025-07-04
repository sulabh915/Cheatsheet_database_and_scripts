
SUBDOMAIN ENUMERATION ALL POSSIBLE COMBINATION:

dig:
```bash
dig axfr @target.com
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
subfinder -dL domains.txt -all -recursive

```

> [!/usr/share/wordlists/seclists/Miscellaneous ] Wordlist for resolver
> 
>/usr/share/wordlists/seclists/Miscellaneous/dns-resolvers.txt

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

```


assetfinder:
```bash
assetfinder subs-only target.com
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


github-subdomains -d example.com -t ~/.tokens -e -raw -o sub_target.txt


# Step 1: Extract subdomains from GitHub
github-subdomains -d target.com -t ~/.tokens -o gh-subs.txt

# Step 2: Clean, resolve, and probe
cat gh-subs.txt | dnsx -silent -o resolved.txt
cat resolved.txt | httpx -silent -title -status-code -o live-assets.txt

generate api token from developer setting from github.
```

vhost enumeration :
```bash
ffuf -w wordlist.txt -u https://target.com -H "Host: FUZZ.target.com"
cat subdomains.txt | httpx -title -web-server -ip -status-code -o vhosts.txt


https://www.ipneighbour.com/#/lookup/feedingindia.org
https://www.yougetsignal.com/tools/web-sites-on-web-server/
```


Combine , sort and unique :
```bash
cat file1.txt file2.txt file3.txt > combined.txt 
sort combined.txt | uniq > sorted_unique.txt
cat file1.txt file2.txt file3.txt | sort | anew finalnokia.txt 
cat file1.txt file2.txt file3.txt | sort -u > finalnokia.txt
cat file1 file2 | sort -u | tee final.txt
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
cat recon/example/domains.txt | httprobe
cat subexample.com.txt | httpx-toolkit -ports 80,443,8080,8000,8888 -threads 200 > subexample.coms_alive.txt
```


Search using favicon hash:

upload the favicon to this site :
https://www.zoomeye.ai/?q=aWNvbmhhc2g9IjE1OTNmMTQ2NTBlNGIzOTM0ZDJhNmI0NmQ4NDRlOTA2Ig%3D%3D




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

##### Screenshoting :
```bash
gowitness scan file -f live.txt --write-db --write-jsonl --write-csv --screenshot-format png --screenshot-fullpage --threads 15 

gowitness scan cidr -c 10.10.1.0/24 --threads 20 --write-db
gowitness scan nmap -f ./nmap.xml --port 443 --screenshot-fullpage

```

