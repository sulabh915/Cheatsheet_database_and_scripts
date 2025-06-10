

using feroxbuster :
```bash
feroxbuster -u http://192.168.1.4
feroxbuster -u http://192.168.1.4 --silent
feroxbuster -u http://192.168.1.4 -r
feroxbuster -u http://192.168.1.4 -x php,txt --silent
feroxbuster -u http://192.168.1.4 --output results.txt
feroxbuster -u http://192.168.1.4 -a "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
feroxbuster -u http://192.168.1.4 -C 403,404
feroxbuster -u http://192.168.1.4 -t 20
feroxbuster -u http://192.168.1.4 -w /usr/share/wordlists/dirb/common.txt
feroxbuster -u http://192.168.1.4 -n
feroxbuster -u http://192.168.1.4 -L 4
feroxbuster -u http://192.168.1.4 --force-recursion
feroxbuster -u http://192.168.1.4 -q
feroxbuster -u http://192.168.1.4 -q -S 285,286,283,289
feroxbuster -u http://192.168.1.4 -q
feroxbuster -u http://192.168.1.4 -q -W 33
feroxbuster -u http://192.168.1.4 -q
feroxbuster -u http://192.168.1.4 -q -N 9
feroxbuster -u http://192.168.1.4 -q
feroxbuster -u http://192.168.1.4 -q --filter-status 404
feroxbuster -u http://192.168.1.4 -q
feroxbuster -u http://192.168.1.4 -q --status-codes 200,301
feroxbuster -u http://192.168.1.4 -A --burp
feroxbuster -u http://192.168.1.4 -m POST
feroxbuster -u http://192.168.1.4 -H 'Content-Type: application/x-www-form-urlencoded' --burp -q
feroxbuster -u http://192.168.1.4 --cookies PHPSESSID=t54ij15l5d51i2tc7j1k1tu4p4 --burp -q
```

Katana :
```bash
katana -u https://example.com
katana -u https://example.com
katana -u https://example.com -headless -jc
katana -u https://target.com -headless -jc -depth 5 -json -o output.json
katana -list live.txt -match-regex "admin|login|setup" -json -o matched.json
katana -u https://target.com -delay 3 -rate-limit 10 -concurrency 3 -json -o stealth.json
katana -u https://target.com -headless -jc -aff -fx -json -o forms.json
katana -u https://target.com -field qurl
```

Gospider:
```bash
gospider -s https://target.com -d 3 -o output 
gospider -s https://target.com -d 3 -o output --js
gospider -S targets.txt -a -r -w -subs -d 2 -o spider-out
gospider -s https://target.com -q -t 1 -c 2 -k 2 -K 2 -p http://127.0.0.1:8080
gospider -s https://target.com -u "MyCustomAgent" --burp burp.req
gospider -s https://target.com --whitelist "admin|login" -q
```

Gobuster :
```bash
gobuster dir -u https://target.com -w /path/to/wordlist.txt
gobuster dir -u https://target.com -w /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt -x php,txt,bak -t 40 -k -o gobuster-dir.txt
gobuster dns -d target.com -w /path/to/subdomains.txt
gobuster vhost -u http://IP_ADDRESS -w /path/to/wordlist.txt
gobuster fuzz -u https://target.com/api/FUZZ -w endpoints.txt -t 40 -k
gobuster s3 -w bucket-names.txt
```

Used burpsuite crawler 

- Check robots.txt
- Check meta tags informatino lekeage
- Check sitemap.xml of website
- Check security.txt  “wget --no-verbose https://www.linkedin.com/.well-known/security.txt && cat security.txt”
- Check  Human.txt



> [!INFO]
> When crawling your target, always crawl with 2 separate user-agent headers, one for desktop and one for mobile devices and look for response changes! 👀 Some applications deploy multiple versions for different platforms, often containing different features, endpoints, or even authentication mechanisms!
