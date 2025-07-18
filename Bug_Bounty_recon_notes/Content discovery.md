

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
katana -u https://target.com -field url

katana -u livesubdomains.txt -d 2 -o urls.txt
cat urls.txt | hakrawler -u > urls3.txt
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

```bash
cat filtered-files.txt | cut -d '/' -f4- | sort -u > filepaths.txt
```

```bash
assetfinder --subs-only bugcrowd.com | tee subs.txt | httprobe | anew hosts; med -d 1000 -v /
```

```bash
python3 thetimemachine.py example.com --fetch
```

```bash
cat livesubdomains.txt | gau | sort -u > urls2.txt
urlfinder -d tesla.com | sort -u >urls3.txt
echo example.com | gau --mc 200 | urldedupe >urls.txtcat urls.txt | grep -E ".php|.asp|.aspx|.jspx|.jsp" | grep '=' | sort > output.txtcat output.txt | sed 's/=.*/=/' >final.txt
```

```bash
dirsearch -u https://example.com  --full-url --deep-recursive -r
dirsearch -u https://example.com -e php,cgi,htm,html,shtm,shtml,js,txt,bak,zip,old,conf,log,pl,asp,aspx,jsp,sql,db,sqlite,mdb,tar,gz,7z,rar,json,xml,yml,yaml,ini,java,py,rb,php3,php4,php5 --random-agent --recursive -R 3 -t 20 --exclude-status=404 --follow-redirects --delay=0.1
```

```bash
dirsearch -u https://example.com  --full-url --deep-recursive -r
dirsearch -u https://example.com -e php,cgi,htm,html,shtm,shtml,js,txt,bak,zip,old,conf,log,pl,asp,aspx,jsp,sql,db,sqlite,mdb,tar,gz,7z,rar,json,xml,yml,yaml,ini,java,py,rb,php3,php4,php5 --random-agent --recursive -R 3 -t 20 --exclude-status=404 --follow-redirects --delay=0.1

ffuf -w seclists/Discovery/Web-Content/directory-list-2.3-big.txt -u https://example.com/FUZZ -fc 400,401,402,403,404,429,500,501,502,503 -recursion -recursion-depth 2 -e .html,.php,.txt,.pdf,.js,.css,.zip,.bak,.old,.log,.json,.xml,.config,.env,.asp,.aspx,.jsp,.gz,.tar,.sql,.db -ac -c -H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0" -H "X-Forwarded-For: 127.0.0.1" -H "X-Originating-IP: 127.0.0.1" -H "X-Forwarded-Host: localhost" -t 100 -r -o results.json
ffuf -w seclists/Discovery/Web-Content/directory-list-2.3-big.txt -u https://ens.domains/FUZZ  -fc 401,403,404  -recursion -recursion-depth 2 -e .html,.php,.txt,.pdf -ac -H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0" -r -t 60 --rate 100 -c
```


Used burpsuite crawler 

- Check robots.txt
- Check meta tags informatino lekeage
- Check sitemap.xml of website
- Check security.txt  “wget --no-verbose https://www.linkedin.com/.well-known/security.txt && cat security.txt”
- Check  Human.txt



> [!INFO]
> When crawling your target, always crawl with 2 separate user-agent headers, one for desktop and one for mobile devices and look for response changes! 👀 Some applications deploy multiple versions for different platforms, often containing different features, endpoints, or even authentication mechanisms!
