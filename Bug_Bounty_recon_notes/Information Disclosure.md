
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