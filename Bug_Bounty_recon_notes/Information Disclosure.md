
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
```

using nuclei tool:
```bash
nuclei -u https://target.com -t exposures/ -v
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